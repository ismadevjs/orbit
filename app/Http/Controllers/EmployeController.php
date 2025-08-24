<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;

class EmployeController extends Controller
{
    public function index()
    {
        $countries = json_decode(File::get(public_path('countries.json')));
        return view('backend.employees.employees', compact('countries'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // unique email
            'job_id' => 'nullable', // Assuming job_id relates to a jobs table
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            // Create a new user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->email), // Hash the password
            ]);

            if (!$user) {
                throw new \Exception('Failed to create user');
            }

            Log::info('User created successfully:', ['user_id' => $user->id]);

            // Ensure the 'employee' role exists for the 'web' guard
            $employeeRole = Role::firstOrCreate([
                'name' => 'employee',
                'guard_name' => 'web',
            ]);

            // Assign the 'employee' role to the user (using Spatie)
            $user->assignRole($employeeRole);

            Log::info('Employee role assigned to user:', ['user_id' => $user->id]);

            // Create a new employee using the user_id from the created user
            $employee = Employe::create([
                'user_id' => $user->id, // Get user_id from the newly created user
                'job_id' => $request->job_id, // Optional field
            ]);

            Log::info('Employee record created:', ['employee_id' => $employee->id]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Employee created successfully!',
                'employee' => $employee,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Employee Creation Failed:', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create employee!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }




    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email' ,
            'job_id' => 'nullable', // Optional field
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the employee by ID
        $employee = Employe::find($id);

        // Check if the employee exists
        if (!$employee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        // Find the associated user
        $user = User::find($employee->user_id);

        if (!$user) {
            return response()->json(['error' => 'User associated with the employee not found'], 404);
        }

        // Update the user details
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update the employee details
        $employee->update([
            'job_id' => $request->job_id, // Optional field
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Employee updated successfully!',
            'employee' => $employee,
        ]);
    }




    public function destroy($id)
    {
        $employee = Employe::findOrFail($id);
        $employee->delete();

        return response()->json(['success' => true, 'message' => 'Employe deleted successfully!']);
    }

    public function apiIndex()
    {
        return DataTables::of(Employe::query())
            ->addIndexColumn()
            ->addColumn('user_id', function ($row) {
                return $row->user ? $row->user->name : 'N/A';
            })
            ->addColumn('image', function ($row) {
                return $row->user ? $row->user->avatar : 'N/A';
            })
            ->addColumn('about', function ($row) {
                return $row->user ? $row->user->about : 'N/A';
            })
            ->addColumn('address', function ($row) {
                return $row->user ? $row->user->address : 'N/A';
            })
            ->addColumn('gender', function ($row) {
                return $row->user ? $row->user->gender : 'N/A';
            })
            ->addColumn('date_of_birth', function ($row) {
                return $row->user ? $row->user->date_of_birth : 'N/A';
            })
            ->addColumn('country', function ($row) {
                return $row->user ? $row->user->country : 'N/A';
            })
            ->addColumn('email', function ($row) {
                return $row->user ? $row->user->email : 'N/A';
            })
            ->addColumn('name', function ($row) {
                return $row->user ? $row->user->name : 'N/A';
            })
            ->addColumn('job_id', function ($row) {
                return $row->job ? $row->job->name : 'N/A';
            })
            ->make(true);
    }

    public function show($id)
    {
        $employee = Employe::with('user')->findOrFail($id);
        return response()->json($employee);
    }
}
