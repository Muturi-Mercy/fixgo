<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\BreakdownRequest;
use App\Models\Mechanic;
use App\Models\RatingReview;
use App\Models\ServiceCategory;
use App\Models\User;
use App\Models\VehicleCategory;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers      = User::where('role', 'user')->count();
        $totalMechanics  = User::where('role', 'mechanic')->count();
        $totalRequests   = BreakdownRequest::count();
        $completedToday  = BreakdownRequest::where('status', 'completed')
            ->whereDate('completed_at', today())->count();
        $totalEarnings   = BreakdownRequest::where('status', 'completed')->sum('price');
        $pendingMechanics = Mechanic::where('verification_status', 'pending')->count();

        // Recent mechanic registrations
        $recentMechanics = Mechanic::with('user')
            ->latest()->take(5)->get();

        // Recent requests
        $recentRequests = BreakdownRequest::with(['user', 'mechanic.user', 'serviceCategory'])
            ->latest()->take(5)->get();

        // Requests by status for donut chart
        $requestsByStatus = [
            'pending'    => BreakdownRequest::where('status', 'pending')->count(),
            'accepted'   => BreakdownRequest::where('status', 'accepted')->count(),
            'on_the_way' => BreakdownRequest::where('status', 'on_the_way')->count(),
            'completed'  => BreakdownRequest::where('status', 'completed')->count(),
            'cancelled'  => BreakdownRequest::where('status', 'cancelled')->count(),
        ];

        // Top services
        $topServices = BreakdownRequest::select('service_category_id')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('service_category_id')
            ->with('serviceCategory')
            ->orderBy('total', 'desc')
            ->take(5)->get();

        // Weekly requests for line chart (last 7 days)
        $weeklyRequests = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weeklyRequests[] = [
                'date'  => $date->format('M d'),
                'total' => BreakdownRequest::whereDate('created_at', $date)->count(),
                'completed' => BreakdownRequest::whereDate('created_at', $date)
                    ->where('status', 'completed')->count(),
            ];
        }

        // Recent reviews
        $recentReviews = RatingReview::with(['user', 'mechanic.user'])
            ->latest()->take(3)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalMechanics', 'totalRequests',
            'completedToday', 'totalEarnings', 'pendingMechanics',
            'recentMechanics', 'recentRequests', 'requestsByStatus',
            'topServices', 'weeklyRequests', 'recentReviews'
        ));
    }

    public function users()
    {
        $query = User::where('role', 'user');
        if (request('search')) {
            $query->where(function($q) {
                $q->where('name', 'like', '%'.request('search').'%')
                  ->orWhere('email', 'like', '%'.request('search').'%');
            });
        }
        if (request('status')) {
            $query->where('status', request('status'));
        }
        $users = $query->latest()->paginate(15);
        return view('admin.users', compact('users'));
    }

    public function viewUser($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        $requests = BreakdownRequest::where('user_id', $id)
            ->with(['serviceCategory', 'mechanic.user'])
            ->latest()->paginate(10);
        return view('admin.user-view', compact('user', 'requests'));
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        $newStatus = $user->status === 'active' ? 'suspended' : 'active';
        $user->update(['status' => $newStatus]);
        return back()->with('success', 'User status updated to ' . $newStatus . '.');
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    public function mechanics()
    {
        $query = Mechanic::with('user');
        if (request('search')) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%'.request('search').'%');
            });
        }
        if (request('status')) {
            $query->where('verification_status', request('status'));
        }
        $mechanics = $query->latest()->paginate(15);
        $pendingCount = Mechanic::where('verification_status', 'pending')->count();
        return view('admin.mechanics', compact('mechanics', 'pendingCount'));
    }

    public function viewMechanic($id)
    {
        $mechanic = Mechanic::with([
            'user', 'documents', 'portfolios.images',
            'ratings.user', 'breakdownRequests.serviceCategory'
        ])->findOrFail($id);
        return view('admin.mechanic-view', compact('mechanic'));
    }

    public function approveMechanic($id)
    {
        Mechanic::findOrFail($id)->update(['verification_status' => 'approved']);
        return back()->with('success', 'Mechanic approved successfully.');
    }

    public function rejectMechanic($id)
    {
        Mechanic::findOrFail($id)->update(['verification_status' => 'rejected']);
        return back()->with('success', 'Mechanic rejected.');
    }

    public function undoRejection($id)
    {
        Mechanic::findOrFail($id)->update(['verification_status' => 'pending']);
        return back()->with('success', 'Mechanic verification reset to pending. You can now approve or reject again.');
    }

    public function toggleMechanicStatus($id)
    {
        $mechanic = Mechanic::with('user')->findOrFail($id);
        $newStatus = $mechanic->user->status === 'active' ? 'suspended' : 'active';
        $mechanic->user->update(['status' => $newStatus]);
        return back()->with('success', 'Mechanic status updated to ' . $newStatus . '.');
    }

    public function requests()
    {
        $query = BreakdownRequest::with(['user', 'mechanic.user', 'serviceCategory']);
        if (request('status')) {
            $query->where('status', request('status'));
        }
        if (request('search')) {
            $query->where('request_number', 'like', '%'.request('search').'%');
        }
        $requests = $query->latest()->paginate(15);
        return view('admin.requests', compact('requests'));
    }

    public function viewRequest($id)
    {
        $request = BreakdownRequest::with([
            'user', 'mechanic.user', 'serviceCategory',
            'vehicleCategory', 'photos', 'rating', 'chat.sender'
        ])->findOrFail($id);
        return view('admin.request-view', compact('request'));
    }

    public function categories()
    {
        $serviceCategories = ServiceCategory::withCount('breakdownRequests')->get();
        $vehicleCategories = VehicleCategory::withCount('breakdownRequests')->get();
        return view('admin.categories', compact('serviceCategories', 'vehicleCategories'));
    }

    public function storeServiceCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100|unique:service_categories,name']);
        ServiceCategory::create(['name' => $request->name]);
        return back()->with('success', 'Service category added.');
    }

    public function deleteServiceCategory($id)
    {
        ServiceCategory::findOrFail($id)->delete();
        return back()->with('success', 'Service category deleted.');
    }

    public function storeVehicleCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100|unique:vehicle_categories,name']);
        VehicleCategory::create(['name' => $request->name]);
        return back()->with('success', 'Vehicle category added.');
    }

    public function deleteVehicleCategory($id)
    {
        VehicleCategory::findOrFail($id)->delete();
        return back()->with('success', 'Vehicle category deleted.');
    }

    public function reports()
    {
        $totalRevenue = BreakdownRequest::where('status', 'completed')->sum('price');
        $thisMonthRevenue = BreakdownRequest::where('status', 'completed')
            ->whereMonth('completed_at', now()->month)->sum('price');
        $lastMonthRevenue = BreakdownRequest::where('status', 'completed')
            ->whereMonth('completed_at', now()->subMonth()->month)->sum('price');
        $totalCompleted = BreakdownRequest::where('status', 'completed')->count();
        $totalCancelled = BreakdownRequest::where('status', 'cancelled')->count();
        $avgRating = RatingReview::avg('rating');

        // Monthly revenue for last 6 months
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyRevenue[] = [
                'month'   => $month->format('M Y'),
                'revenue' => BreakdownRequest::where('status', 'completed')
                    ->whereYear('completed_at', $month->year)
                    ->whereMonth('completed_at', $month->month)
                    ->sum('price'),
                'requests' => BreakdownRequest::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)->count(),
            ];
        }

        // Top mechanics by jobs
        $topMechanics = Mechanic::with('user')
            ->orderBy('total_jobs', 'desc')
            ->take(5)->get();

        // Top services
        $topServices = BreakdownRequest::select('service_category_id')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('service_category_id')
            ->with('serviceCategory')
            ->orderBy('total', 'desc')
            ->take(5)->get();

        return view('admin.reports', compact(
            'totalRevenue', 'thisMonthRevenue', 'lastMonthRevenue',
            'totalCompleted', 'totalCancelled', 'avgRating',
            'monthlyRevenue', 'topMechanics', 'topServices'
        ));
    }

    public function reviews()
    {
        $reviews = RatingReview::with(['user', 'mechanic.user', 'breakdownRequest'])
            ->latest()->paginate(15);
        $avgRating = RatingReview::avg('rating');
        $totalReviews = RatingReview::count();
        return view('admin.reviews', compact('reviews', 'avgRating', 'totalReviews'));
    }

    public function deleteReview($id)
    {
        RatingReview::findOrFail($id)->delete();
        return back()->with('success', 'Review deleted.');
    }

    public function announcements()
    {
        $announcements = Announcement::with('admin')->latest()->paginate(10);
        return view('admin.announcements', compact('announcements'));
    }

   public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
            'target'  => 'required|in:all,users,mechanics',
        ]);

        Announcement::create([
            'admin_id' => auth()->id(),
            'title'    => $request->title,
            'message'  => $request->message,
            'target'   => $request->target,
        ]);

        // Send notification to target users
        $query = User::query();
        if ($request->target === 'users') {
            $query->where('role', 'user');
        } elseif ($request->target === 'mechanics') {
            $query->where('role', 'mechanic');
        } else {
            $query->whereIn('role', ['user', 'mechanic']);
        }

        $users = $query->get();
        foreach ($users as $user) {
            $user->notify(new \App\Notifications\GeneralNotification(
                $request->title,
                $request->message,
                'announcement'
            ));
        }

        return back()->with('success', 'Announcement sent to '.$users->count().' users!');
    }

    public function deleteAnnouncement($id)
    {
        Announcement::findOrFail($id)->delete();
        return back()->with('success', 'Announcement deleted.');
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function requestsAnalytics()
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = [
                'date'      => $date->format('M d'),
                'total'     => BreakdownRequest::whereDate('created_at', $date)->count(),
                'completed' => BreakdownRequest::whereDate('created_at', $date)
                    ->where('status', 'completed')->count(),
            ];
        }
        return response()->json($data);
    }
}