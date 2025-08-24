<?php

namespace App\Http\Controllers;

use App\Models\roleHasPermission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{


    public function stuffPermission()
    {
        $roles = Role::get();
        return view("backend.stuff.permissions.list", compact('roles'));
    }

    public function getStuffPermissionsData()
    {
        $roles = Role::select(['id', 'name', 'updated_at', 'created_at']);

        return DataTables::of($roles)
            ->addIndexColumn() // Adds the index column
            ->addColumn('actions', function ($row) {
                $editUrl = route('stuff.permissions.edit', ['id' => $row->id]);
                $deleteUrl = route('stuff.permissions.delete', ['id' => $row->id]);

                return '<ul class="list-inline me-auto mb-0">
                        <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="' . __('messages.edit') . '">
                            <a href="' . $editUrl . '" class="avtar avtar-xs btn-link-success btn-pc-default">
                                <i class="fas fa-edit"></i>
                            </a>
                        </li>
                        <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="' . __('messages.delete') . '">
                            <a href="#" data-id="' . $row->id . '" class="avtar avtar-xs btn-link-danger btn-pc-default delete-role">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </li>
                    </ul>';
            })
            ->editColumn('updated_at', function ($row) {
                return $row->updated_at->diffForHumans(); // Formats the date to a human-readable format
            })
            ->rawColumns(['actions']) // Ensures HTML in the actions column is rendered properly
            ->make(true);
    }




    public function stuffPermissionAdd()
    {
        return view("backend.stuff.permissions.add");
    }
    public function stuffPermissionEdit($id)
    {
        $role = Role::where('id', $id)->first();
        return view("backend.stuff.permissions.edit", compact('role'));
    }
    public function stuffPermissionPost(Request $request)
    {


        $request->validate([
            "name" => "required",
        ]);

        // Create the role
        $role = Role::create(['name' => $request->name]);

        // Assign permissions to the role
        if ($request->permissions) {
            foreach ($request->permissions as $pr) {
                $permissionName = str_replace('-', ' ', $pr);

                // Check if the permission exists, if not, create it
                $permission = Permission::firstOrCreate(['name' => $permissionName]);

                // Assign the permission to the role
                $role->givePermissionTo($permission);
                $permission->assignRole($role);
            }
        }

        return redirect()->route("stuff.permissions")->withSucecss('Role added and permissions assigned.');
    }

    public function stuffPermissionUpdate(Request $request)
    {
        $request->validate([
            "id" => "required|exists:roles,id", // Ensure ID is provided and valid
            "name" => "required",
        ]);

        // Find the role by ID
        $role = Role::find($request->id);

        // Update the role's name
        $role->name = $request->name;
        $role->save(); // Save the changes to the database


        // Clear existing permissions for the role
        roleHasPermission::where("role_id", $role->id)->delete();

        // Re-assign permissions to the role
        if ($request->permissions) {
            foreach ($request->permissions as $pr) {
                $permissionName = str_replace('-', ' ', $pr);

                // Check if the permission exists, if not, create it
                $permission = Permission::firstOrCreate(['name' => $permissionName]);

                // Assign the permission to the role
                $role->givePermissionTo($permission);
                $permission->assignRole($role);
            }
        }

        return back()->withSuccess('Role updated and permissions assigned.');
    }



    public function delete(Request $request)
    {
        $role = Role::where('id', $request->id)->first();


        foreach ($role->permissions as $pr) {
            $role->revokePermissionTo($pr);
            $pr->removeRole($role);
        }


        $role->delete();
        return response()->json(['message' => 'Role has been deleted successfully.']);
    }


    // permissions init
    public function permissions_init()
    {
        foreach (routes_list() as $rl) {
            foreach (permissions_list() as $pl) {
                Permission::firstOrCreate(['name' => $pl . "-" . $rl]); // Use hyphen instead of space
            }
        }
        return back()->withSuccess("Permissions initialized successfully");
    }

    public function roles()
    {
        $stuffs = User::paginate(25);
        $roles = Role::get();
        return view("backend.stuff.roles.list", compact("stuffs", "roles"));
    }
    public function rolesAdd(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            "password" => "required",
            "role" => "required|exists:roles,id", // Ensure role ID is valid
        ]);

        // Create the user
        $data = [
            "name" => $request->name,
            "email" => $request->email,
            "phone" => $request->phone,
            "password" => Hash::make($request->password), // Hash the password
        ];

        $user = User::create($data); // Save the user

        // Assign the role using the role ID
        $role = Role::find($request->role); // Get the role by ID
        if ($role) {
            $user->assignRole($role); // Assign the role to the user
        } else {
            return back()->withErrors(['role' => 'Role not found.']);
        }

        return redirect()->route("stuff.roles")->withSuccess('User added and role assigned.');
    }

    public function rolesUpdate(Request $request)
    {
        $user = User::findOrFail($request->id);

        // Assuming 'role' is the ID
        $roleId = $request->role;
        $role = Role::find($roleId);

        if (!$role) {
            return response()->json(['error' => 'Role does not exist'], 404);
        }

        // Clear existing roles
        $user->syncRoles([$role->name]);

        // Update other user fields
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->withSuccess('User updated successfully');
    }


    public function getData()
    {
        $stuffs = User::with('roles') // Eager load roles
            ->select(['id', 'name', 'email', 'phone', 'created_at']);

        return DataTables::of($stuffs)
            ->addIndexColumn() // Adds the index column
            ->addColumn('roles', function ($user) {
                return $user->roles->pluck('name')->implode(', '); // Get roles as a comma-separated string
            })
            ->addColumn('actions', function ($user) {
                $editUrl = route('stuff.roles.update', ['id' => $user->id]); // Assuming you have an edit route
                $deleteUrl = route('stuff.roles.delete', ['id' => $user->id]);

                return '
                <ul class="list-inline me-auto mb-0">
                    <li class="list-inline-item" data-bs-toggle="tooltip" title="Edit">
                        <a href="' . $editUrl . '" class="avtar avtar-xs btn-link-success btn-pc-default">
                            <i class="fas fa-edit"></i>
                        </a>
                    </li>
                    <li class="list-inline-item" data-bs-toggle="tooltip" title="Delete">
                        <a href="#" data-id="' . $user->id . '" class="avtar avtar-xs btn-link-danger btn-pc-default delete-role">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </li>
                </ul>';
            })
            ->editColumn('created_at', function ($user) {
                return $user->created_at->diffForHumans(); // Format the creation date
            })
            ->rawColumns(['actions']) // Ensure HTML in the actions column is rendered properly
            ->make(true);
    }



    public function dropStuff(Request $request)
    {
        $user = User::find($request->id);
    
        // Check if user exists
        if (!$user) {
            return back()->withErrors(['error' => 'User not found.']);
        }
    
        // Delete related sessions
        $user->sessions()->delete(); // Assumes a "sessions" relationship is defined in the User model
    
        // Detach roles and delete user
        $user->roles()->detach();
        $user->delete();
    
        return back()->withSuccess('User deleted successfully.');
    }

}
