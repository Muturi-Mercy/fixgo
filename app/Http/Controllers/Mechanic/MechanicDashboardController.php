<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\BreakdownRequest;
use App\Models\Mechanic;
use App\Models\MechanicPortfolio;
use App\Models\PortfolioImage;
use App\Models\RatingReview;
use Illuminate\Http\Request;

class MechanicDashboardController extends Controller
{
    public function index()
    {
        $mechanic = auth()->user()->mechanic;

        if (!$mechanic) {
            return redirect()->route('mechanic.dashboard')
                ->with('error', 'Mechanic profile not found.');
        }

        $todayJobs = BreakdownRequest::where('mechanic_id', $mechanic->id)
            ->whereDate('created_at', today())->count();
        $completedJobs = BreakdownRequest::where('mechanic_id', $mechanic->id)
            ->where('status', 'completed')->count();
        $todayEarnings = BreakdownRequest::where('mechanic_id', $mechanic->id)
            ->where('status', 'completed')
            ->whereDate('completed_at', today())
            ->sum('price');
        $totalEarnings = BreakdownRequest::where('mechanic_id', $mechanic->id)
            ->where('status', 'completed')->sum('price');
        $newRequests = BreakdownRequest::where('status', 'pending')
            ->whereNull('mechanic_id')->latest()->take(5)->get();
        $activeJobs = BreakdownRequest::where('mechanic_id', $mechanic->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with(['user', 'serviceCategory'])->get();
        $recentJobs = BreakdownRequest::where('mechanic_id', $mechanic->id)
            ->with(['user', 'serviceCategory'])
            ->latest()->take(5)->get();

        return view('mechanic.dashboard', compact(
            'mechanic', 'todayJobs', 'completedJobs',
            'todayEarnings', 'totalEarnings',
            'newRequests', 'activeJobs', 'recentJobs'
        ));
    }

    public function serviceRequests()
    {
        $mechanic = auth()->user()->mechanic;
        $newRequests = BreakdownRequest::where('status', 'pending')
            ->whereNull('mechanic_id')
            ->with(['user', 'serviceCategory', 'vehicleCategory'])
            ->latest()->get();
        $acceptedRequests = BreakdownRequest::where('mechanic_id', $mechanic->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with(['user', 'serviceCategory'])
            ->latest()->get();
        $completedRequests = BreakdownRequest::where('mechanic_id', $mechanic->id)
            ->where('status', 'completed')
            ->with(['user', 'serviceCategory'])
            ->latest()->take(10)->get();
        return view('mechanic.service-requests', compact(
            'newRequests', 'acceptedRequests', 'completedRequests'
        ));
    }

    public function myJobs()
    {
        $mechanic = auth()->user()->mechanic;
        $query = BreakdownRequest::where('mechanic_id', $mechanic->id)
            ->with(['user', 'serviceCategory', 'vehicleCategory']);

        if (request('status')) {
            $query->where('status', request('status'));
        }

        $jobs = $query->latest()->paginate(10);
        return view('mechanic.my-jobs', compact('jobs'));
    }

    public function earnings()
    {
        $mechanic = auth()->user()->mechanic;
        $totalEarnings = BreakdownRequest::where('mechanic_id', $mechanic->id)
            ->where('status', 'completed')->sum('price');
        $thisMonthEarnings = BreakdownRequest::where('mechanic_id', $mechanic->id)
            ->where('status', 'completed')
            ->whereMonth('completed_at', now()->month)
            ->sum('price');
        $recentPayments = BreakdownRequest::where('mechanic_id', $mechanic->id)
            ->where('status', 'completed')
            ->with(['user', 'serviceCategory'])
            ->latest()->take(10)->get();
        return view('mechanic.earnings', compact(
            'totalEarnings', 'thisMonthEarnings', 'recentPayments'
        ));
    }

    public function portfolio()
    {
        $mechanic = auth()->user()->mechanic;
        $portfolios = MechanicPortfolio::where('mechanic_id', $mechanic->id)
            ->with('images')->latest()->get();
        return view('mechanic.portfolio', compact('portfolios', 'mechanic'));
    }

    public function createPortfolio()
    {
        return view('mechanic.portfolio-create');
    }

    public function storePortfolio(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|string',
            'description' => 'nullable|string',
            'work_date'   => 'nullable|date',
            'images.*'    => 'nullable|image|max:3072',
            'image_types.*' => 'nullable|string',
        ]);

        $mechanic = auth()->user()->mechanic;

        $portfolio = MechanicPortfolio::create([
            'mechanic_id' => $mechanic->id,
            'title'       => $request->title,
            'category'    => $request->category,
            'description' => $request->description,
            'work_date'   => $request->work_date,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('portfolio-images', 'public');
                PortfolioImage::create([
                    'mechanic_portfolio_id' => $portfolio->id,
                    'image_path'            => $path,
                    'type' => $request->image_types[$index] ?? 'general',
                ]);
            }
        }

        return redirect()->route('mechanic.portfolio')
            ->with('success', 'Portfolio work added successfully!');
    }

    public function deletePortfolio($id)
    {
        $mechanic = auth()->user()->mechanic;
        $portfolio = MechanicPortfolio::where('mechanic_id', $mechanic->id)
            ->findOrFail($id);
        $portfolio->delete();
        return back()->with('success', 'Portfolio item deleted.');
    }

    public function reviews()
    {
        $mechanic = auth()->user()->mechanic;
        $reviews = RatingReview::where('mechanic_id', $mechanic->id)
            ->with('user')->latest()->paginate(10);
        $avgRating = RatingReview::where('mechanic_id', $mechanic->id)->avg('rating');
        return view('mechanic.reviews', compact('reviews', 'avgRating'));
    }

    public function profile()
    {
        $mechanic = auth()->user()->mechanic;
        return view('mechanic.profile', compact('mechanic'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'                => 'required|string|max:255',
            'phone'               => 'nullable|string|max:20',
            'bio'                 => 'nullable|string',
            'specialization'      => 'nullable|string',
            'years_of_experience' => 'nullable|integer',
            'location_address'    => 'nullable|string',
            'min_price'           => 'nullable|numeric',
            'max_price'           => 'nullable|numeric',
        ]);

        auth()->user()->update([
            'name'  => $request->name,
            'phone' => $request->phone,
        ]);

        auth()->user()->mechanic->update([
            'bio'                 => $request->bio,
            'specialization'      => $request->specialization,
            'years_of_experience' => $request->years_of_experience,
            'location_address'    => $request->location_address,
            'min_price'           => $request->min_price,
            'max_price'           => $request->max_price,
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

        auth()->user()->update(['password' => \Hash::make($request->password)]);
        return back()->with('success', 'Password updated successfully!');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate(['profile_photo' => 'required|image|max:2048']);
        $path = $request->file('profile_photo')->store('profile-photos', 'public');
        auth()->user()->update(['profile_photo' => $path]);
        return back()->with('success', 'Profile photo updated!');
    }

    public function updateAvailability(Request $request)
    {
        $mechanic = auth()->user()->mechanic;
        $mechanic->update(['availability' => $request->availability]);
        return response()->json(['success' => true, 'availability' => $request->availability]);
    }

    public function acceptRequest($id)
    {
        $mechanic = auth()->user()->mechanic;
        $request = BreakdownRequest::where('status', 'pending')
            ->whereNull('mechanic_id')->findOrFail($id);
        $request->update([
            'mechanic_id' => $mechanic->id,
            'status'      => 'accepted',
            'accepted_at' => now(),
        ]);
        return back()->with('success', 'Request accepted!');
    }

    public function declineRequest($id)
    {
        BreakdownRequest::findOrFail($id);
        return back()->with('info', 'Request declined.');
    }

    public function updateRequestStatus(Request $request, $id)
    {
        $mechanic = auth()->user()->mechanic;
        $breakdownRequest = BreakdownRequest::where('mechanic_id', $mechanic->id)
            ->findOrFail($id);
        $breakdownRequest->update([
            'status'       => $request->status,
            'completed_at' => $request->status === 'completed' ? now() : null,
        ]);
        return back()->with('success', 'Status updated!');
    }
}