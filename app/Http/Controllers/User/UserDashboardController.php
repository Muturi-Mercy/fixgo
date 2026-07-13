<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BreakdownRequest;
use App\Models\Mechanic;
use App\Models\ServiceCategory;
use App\Models\VehicleCategory;
use App\Models\User;
use Illuminate\Http\Request;


class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $totalRequests = BreakdownRequest::where('user_id', $user->id)->count();
        $activeRequests = BreakdownRequest::where('user_id', $user->id)
            ->whereNotIn('status', ['completed', 'cancelled'])->count();
        $completedRequests = BreakdownRequest::where('user_id', $user->id)
            ->where('status', 'completed')->count();
        $favourites = $user->favourites()->count();
        $recentRequests = BreakdownRequest::where('user_id', $user->id)
            ->with(['serviceCategory', 'mechanic.user'])
            ->latest()->take(5)->get();
        $activeRequest = BreakdownRequest::where('user_id', $user->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with(['mechanic.user', 'serviceCategory'])
            ->latest()->first();

        return view('user.dashboard', compact(
            'totalRequests', 'activeRequests', 'completedRequests',
            'favourites', 'recentRequests', 'activeRequest'
        ));
    }

    public function requestAssistance()
    {
        $serviceCategories = ServiceCategory::where('is_active', true)->get();
        $vehicleCategories = VehicleCategory::where('is_active', true)->get();
        return view('user.request-assistance', compact('serviceCategories', 'vehicleCategories'));
    }

    public function myRequests()
        {
            $query = BreakdownRequest::where('user_id', auth()->id())
                ->with(['serviceCategory', 'vehicleCategory', 'mechanic.user', 'rating']);

            if (request('status')) {
                $query->where('status', request('status'));
            }

            $requests = $query->latest()->paginate(10);
            return view('user.my-requests', compact('requests'));
        }

    public function cancelRequest($id)
        {
            $request = BreakdownRequest::where('user_id', auth()->id())
                ->where('status', 'pending')
                ->findOrFail($id);
            $request->update(['status' => 'cancelled']);
            return back()->with('success', 'Request cancelled successfully.');
        }

    public function rateRequest(Request $request)
        {
            $request->validate([
                'request_id' => 'required|exists:breakdown_requests,id',
                'rating'     => 'required|integer|min:1|max:5',
                'review'     => 'nullable|string',
            ]);

            $breakdownRequest = BreakdownRequest::where('user_id', auth()->id())
                ->findOrFail($request->request_id);

            \App\Models\RatingReview::create([
                'breakdown_request_id' => $breakdownRequest->id,
                'user_id'              => auth()->id(),
                'mechanic_id'          => $breakdownRequest->mechanic_id,
                'rating'               => $request->rating,
                'review'               => $request->review,
            ]);

            // Update mechanic average rating
            if ($breakdownRequest->mechanic_id) {
                $avgRating = \App\Models\RatingReview::where('mechanic_id', $breakdownRequest->mechanic_id)
                    ->avg('rating');
                \App\Models\Mechanic::where('id', $breakdownRequest->mechanic_id)
                    ->update(['rating' => round($avgRating, 2)]);
            }

            return back()->with('success', 'Thank you for your review!');
        }

    public function requestDetails($id)
        {
            $req = BreakdownRequest::where('user_id', auth()->id())
                ->with(['serviceCategory', 'vehicleCategory', 'mechanic.user'])
                ->findOrFail($id);

            return response()->json([
                'request_number' => $req->request_number,
                'service'        => $req->serviceCategory->name ?? 'N/A',
                'vehicle'        => $req->vehicleCategory->name ?? 'N/A',
                'status'         => ucwords(str_replace('_', ' ', $req->status)),
                'price'          => $req->price ? 'KSh '.number_format($req->price) : 'Not set',
                'address'        => $req->user_address ?? 'N/A',
                'problem'        => $req->problem_description,
                'mechanic'       => $req->mechanic->user->name ?? 'Not assigned',
                'date'           => $req->created_at->format('M d, Y h:i A'),
            ]);
        }

   public function mechanics()
    {
        $query = Mechanic::where('verification_status', 'approved')
            ->with(['user', 'ratings']);

        if (request('search')) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%'.request('search').'%');
            })->orWhere('specialization', 'like', '%'.request('search').'%');
        }

        if (request('specialization')) {
            $query->where('specialization', 'like', '%'.request('specialization').'%');
        }

        $sort = request('sort', 'rating');
        if ($sort === 'rating') $query->orderBy('rating', 'desc');
        elseif ($sort === 'jobs') $query->orderBy('total_jobs', 'desc');
        elseif ($sort === 'experience') $query->orderBy('years_of_experience', 'desc');

        $mechanics = $query->get();
        return view('user.mechanics', compact('mechanics'));
    }

    public function mechanicProfile($id)
    {
        $mechanic = Mechanic::with([
            'user',
            'portfolios.images',
            'ratings.user'
        ])->findOrFail($id);

        return view('user.mechanic-profile', compact('mechanic'));
    }

    public function trackMechanic($id)
    {
        $request = BreakdownRequest::with(['mechanic.user'])
            ->findOrFail($id);
        return view('user.track', compact('request'));
    }

    public function favourites()
    {
        $favourites = \App\Models\Favourite::where('user_id', auth()->id())
            ->with(['mechanic.user', 'mechanic.ratings'])
            ->get();
        return view('user.favourites', compact('favourites'));
    }

    public function notifications()
    {
        return view('user.notifications');
    }

    public function profile()
    {
        $user = auth()->user();
        $totalRequests = BreakdownRequest::where('user_id', $user->id)->count();
        $completedRequests = BreakdownRequest::where('user_id', $user->id)
            ->where('status', 'completed')->count();
        $favourites = \App\Models\Favourite::where('user_id', $user->id)->count();
        return view('user.profile', compact('totalRequests', 'completedRequests', 'favourites'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.auth()->id(),
            'phone' => 'nullable|string|max:20',
        ]);

        auth()->user()->update([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:6|confirmed',
        ]);

        if (!\Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        auth()->user()->update([
            'password' => \Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|max:2048',
        ]);

        $path = $request->file('profile_photo')->store('profile-photos', 'public');
        auth()->user()->update(['profile_photo' => $path]);

        return back()->with('success', 'Profile photo updated!');
    }
    public function storeRequest(Request $request)
    {
        $request->validate([
            'vehicle_category_id'  => 'required|exists:vehicle_categories,id',
            'service_category_id'  => 'required|exists:service_categories,id',
            'problem_description'  => 'required|string',
            'user_latitude'        => 'required',
            'user_longitude'       => 'required',
        ]);

        // Generate unique request number
        $requestNumber = 'REQ#' . strtoupper(uniqid());

        $breakdownRequest = BreakdownRequest::create([
            'request_number'      => $requestNumber,
            'user_id'             => auth()->id(),
            'service_category_id' => $request->service_category_id,
            'vehicle_category_id' => $request->vehicle_category_id,
            'problem_description' => $request->problem_description,
            'user_latitude'       => $request->user_latitude,
            'user_longitude'      => $request->user_longitude,
            'user_address'        => $request->user_address,
            'status'              => 'pending',
        ]);

        // Handle photo uploads
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('request-photos', 'public');
                \App\Models\RequestPhoto::create([
                    'breakdown_request_id' => $breakdownRequest->id,
                    'photo_path'           => $path,
                ]);
            }
        }

        return redirect()->route('user.my-requests')
            ->with('success', 'Your request has been submitted! Nearby mechanics will be notified.');
    }

    public function toggleFavourite($id)
    {
        $existing = \App\Models\Favourite::where('user_id', auth()->id())
            ->where('mechanic_id', $id)->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['favourited' => false]);
        }

        \App\Models\Favourite::create([
            'user_id'     => auth()->id(),
            'mechanic_id' => $id,
        ]);

        return response()->json(['favourited' => true]);
    }
}