<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminEmployeeController extends Controller
{
    /**
     * Display a listing of employees with search
     */
    public function index(Request $request)
    {
        $query = User::kasir()->with('employee');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhereHas('employee', function($q2) use ($search) {
                      $q2->where('nik', 'LIKE', "%{$search}%")
                         ->orWhere('phone', 'LIKE', "%{$search}%");
                  });
            });
        }

        $staff = $query->latest()->paginate(15);

        return view('admin.staff.index', compact('staff'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.staff.create');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $staff = User::kasir()->with('employee')->findOrFail($id);
        return view('admin.staff.edit', compact('staff'));
    }

    /**
     * Store a newly created employee
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'nik' => 'required|string|size:16|unique:employees,nik',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Create user account for kasir
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'kasir',
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'is_active' => true,
            ]);

            // Handle photo upload
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('employees', 'public');
            }

            // Create employee record
            $employee = Employee::create([
                'user_id' => $user->id,
                'nik' => $validated['nik'],
                'name' => $validated['name'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'photo' => $photoPath,
            ]);

            DB::commit();

            return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal menambahkan staff: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified employee
     */
    public function show($id)
    {
        $employee = Employee::with('user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $employee,
        ]);
    }

    /**
     * Update the specified employee
     */
    public function update(Request $request, $id)
    {
        $user = User::kasir()->findOrFail($id);
        $employee = $user->employee;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($id)],
            'password' => 'nullable|string|min:6',
            'nik' => ['required', 'string', 'size:16', Rule::unique('employees', 'nik')->ignore($employee->id)],
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            // Update user account
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
            ];

            if (isset($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            if (isset($validated['is_active'])) {
                $userData['is_active'] = $validated['is_active'];
            }

            $user->update($userData);

            // Handle photo upload
            $photoPath = $employee->photo;
            if ($request->hasFile('photo')) {
                // Delete old photo
                if ($employee->photo) {
                    Storage::disk('public')->delete($employee->photo);
                }
                $photoPath = $request->file('photo')->store('employees', 'public');
            }

            // Update employee record
            $employee->update([
                'nik' => $validated['nik'],
                'name' => $validated['name'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'photo' => $photoPath,
            ]);

            DB::commit();

            return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal mengupdate staff: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified employee
     */
    public function destroy($id)
    {
        try {
            $user = User::kasir()->findOrFail($id);
            $employee = $user->employee;

            // Delete photo if exists
            if ($employee && $employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }

            // Delete user account (will cascade delete employee)
            $user->delete();

            return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil dihapus');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus staff: ' . $e->getMessage()]);
        }
    }
}
