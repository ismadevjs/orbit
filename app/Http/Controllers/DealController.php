<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\SignRole;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index()
    {
        return view('backend.deals.deals');
    }

    public function apiIndex()
    {
        $deals = Deal::with(['lead', 'responsible', 'manager'])
            ->whereHas('lead', function ($query) {
                $query->where('status', 'accepted');
            });

        // Check if the user is an admin
        if (Auth::user()->hasRole('admin')) {
            // Admins can see all deals
        } elseif (Auth::user()->hasRole('call center')) {
            // Allow call center users to see their own deals
            $deals = $deals->where('responsible_id', Auth::id());
        } elseif (Auth::user()->hasRole('call center manager')) {
            // Fetch all call centers with the same responsible ID from deals
            $responsibleId = Auth::id();

            // Get the call centers that have the same responsible_id from the deals
            $callCenterIds = SignRole::where('manager_id', $responsibleId)->pluck('call_center_id');

            // Filter deals by those call centers
            $deals = $deals->whereIn('responsible_id', $callCenterIds);
        }

        return DataTables::of($deals)
            ->addIndexColumn()
            ->addColumn('first_name', function ($deal) {
                return $deal->lead ? $deal->lead->first_name : '';
            })
            ->addColumn('last_name', function ($deal) {
                return $deal->lead ? $deal->lead->last_name : '';
            })
            ->addColumn('email', function ($deal) {
                return $deal->lead ? $deal->lead->email : '';
            })
            ->addColumn('phone', function ($deal) {
                return $deal->lead ? $deal->lead->phone : '';
            })
            ->addColumn('responsible_name', function ($deal) {
                return $deal->responsible ? $deal->responsible->name : '';
            })
            ->addColumn('manager_name', function ($deal) {
                return $deal->manager ? $deal->manager->name : '';
            })
            ->addColumn('created_at', function ($deal) {
                $createdAt = Carbon::parse($deal->created_at);
                $now = Carbon::now();

                if ($createdAt->isToday()) {
                    return 'Today';
                }

                $daysAgo = $createdAt->diffInDays($now);
                return $daysAgo . ' day' . ($daysAgo !== 1 ? 's' : '') . ' ago';
            })
            ->addColumn('actions', function ($deal) {
                $user = Auth::user();

                if ($user->hasRole('admin') || ($user->hasPermissionTo('edit responsibles') && $user->hasPermissionTo('browse responsibles'))) {
                    return '<button class="btn btn-primary edit-responsible" data-id="' . $deal->id . '">
                <i class="fas fa-lock"></i>
            </button>';
                }

                return ''; // Return empty string if the user doesn't have the required role or permissions
            })
            ->rawColumns(['actions'])
            ->make(true);
    }




    public function updateResponsible(Request $request, Deal $deal)
    {
        $validated = $request->validate([
            'responsible_id' => 'required|exists:users,id', // Validate responsible_id
        ]);

        $deal->responsible_id = $validated['responsible_id'];
        $deal->save();

        return response()->json(['message' => 'Responsible user updated successfully.']);
    }

    // Fetch roles
    public function roles()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    // Fetch users by role
    public function getUsersByRole($roleId)
    {
        // Validate that the role exists
        $role = SignRole::find($roleId);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        // Get users who have the specified role
        $users = SignRole::with(['callCenter'])->get();

        return response()->json($users);
    }

    public function fetchUsers()
    {

        $callCenters = SignRole::where('manager_id', Auth::id())->get();

        $result = $callCenters->map(function ($signRole) {
            return [
                'id' => $signRole->callCenter->id,
                'name' => $signRole->callCenter->name,
            ];
        });

        \Log::info($callCenters);

        return response()->json($result);
    }


}
