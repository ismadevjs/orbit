<?php
namespace App\Http\Controllers;

use App\Models\SignRole;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SignRoleController extends Controller
{
    // Display the list of roles and the form for assigning manager to call center
    public function index() {
        $managers = User::role('call center manager')->get();
        $callCenters = User::role('call center')->get();
        $roles = SignRole::paginate(10); // Paginated list of roles
        return view('backend.rolesSign.rolesSign', compact('managers', 'callCenters', 'roles'));
    }

    // API endpoint for fetching roles data
    public function apiIndex() {
        $roles = SignRole::with(['manager', 'callCenter']); // Adjust relationships as needed

        return DataTables::of($roles)
            ->addIndexColumn() // Adds index column
            ->editColumn('manager.name', function ($role) {
                return $role->manager ? $role->manager->name : 'N/A'; // Adjust based on your User model
            })
            ->editColumn('callCenter.name', function ($role) {
                return $role->callCenter ? $role->callCenter->name : 'N/A'; // Adjust based on your User model
            })
            ->make(true);
    }

    // Show the details of a specific role
    public function show($id) {
        $role = SignRole::with(['manager', 'callCenter'])->findOrFail($id);
        return response()->json($role);
    }

    public function store(Request $request) {
        $request->validate([
            'manager_id' => 'required|exists:users,id',
            'call_center_id' => 'required|exists:users,id',
        ]);

        $existingRole = SignRole::where('call_center_id', $request->call_center_id)->first();
        if ($existingRole && $existingRole->manager_id != null) {
            return response()->json(['error' => 'Call center already has a manager'], 400);
        }

        $role = SignRole::create([
            'manager_id' => $request->manager_id,
            'call_center_id' => $request->call_center_id,
        ]);

        return response()->json(['success' => 'Manager assigned successfully', 'role' => $role], 200);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'manager_id' => 'required|exists:users,id',
            'call_center_id' => 'required|exists:users,id',
        ]);

        $role = SignRole::findOrFail($id);


        if ($role->call_center_id !== $request->call_center_id) {
            $existingRole = SignRole::where('call_center_id', $request->call_center_id)->first();
            if ($existingRole && $existingRole->manager_id != null) {
                return response()->json(['error' => 'Call center already has a manager'], 400);
            }
        }




        $role->update([
            'manager_id' => $request->manager_id,
            'call_center_id' => $request->call_center_id,
        ]);

        return response()->json(['success' => 'Role updated successfully'], 200);
    }


    public function destroy($id) {
        $role = SignRole::findOrFail($id);
        $role->delete();

        return response()->json(['success' => 'Role deleted successfully'], 200);
    }
}
