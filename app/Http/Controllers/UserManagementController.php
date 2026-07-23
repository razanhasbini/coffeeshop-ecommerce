<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function storeManager(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $manager = User::create($data);
        $manager->forceFill(['role' => 'manager', 'is_admin' => true, 'is_active' => true])->save();
        ActivityLog::record('manager.created', 'Manager account created for '.$manager->email, $manager);

        return back()->with('success', 'Manager account created successfully.');
    }

    public function update(Request $request, User $user)
    {
        abort_if($user->isSuperAdmin(), 422, 'The super-admin account cannot be modified here.');

        $data = $request->validate([
            'role' => ['required', Rule::in(['user', 'manager'])],
            'is_active' => ['required', 'boolean'],
        ]);

        $previous = ['role' => $user->role, 'is_active' => $user->is_active];
        $user->forceFill([
            'role' => $data['role'],
            'is_admin' => $data['role'] === 'manager',
            'is_active' => (bool) $data['is_active'],
        ])->save();

        ActivityLog::record('user.permissions_updated', 'Permissions updated for '.$user->email, $user, [
            'before' => $previous,
            'after' => ['role' => $user->role, 'is_active' => $user->is_active],
        ]);

        return back()->with('success', 'User role and status updated.');
    }
}
