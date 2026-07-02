<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BreakdownRequest;
use App\Models\Mechanic;
use App\Models\ServiceCategory;
use App\Models\VehicleCategory;
use App\Models\User;

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
        $requests = BreakdownRequest::where('user_id', auth()->id())
            ->with(['serviceCategory', 'mechanic.user'])
            ->latest()->paginate(10);
        return view('user.my-requests', compact('requests'));
    }

    public function mechanics()
    {
        $mechanics = Mechanic::where('verification_status', 'approved')
            ->where('availability', 'available')
            ->with('user')
            ->get();
        return view('user.mechanics', compact('mechanics'));
    }

    public function mechanicProfile($id)
    {
        $mechanic = Mechanic::with(['user', 'portfolios.images', 'ratings'])
            ->findOrFail($id);
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
        $favourites = auth()->user()->favourites()->with('mechanic.user')->get();
        return view('user.favourites', compact('favourites'));
    }

    public function notifications()
    {
        return view('user.notifications');
    }

    public function profile()
    {
        return view('user.profile');
    }
}