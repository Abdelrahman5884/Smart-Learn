<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // Get Profile

     public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
      }
      // Get update
public function update(UpdateUserRequest $request)
{
    
    $user = $request->user();

    $data = $request->validated();

    if ($request->hasFile('profile_image')) {

    if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
        Storage::disk('public')->delete($user->profile_image);
    }

    $path = $request->file('profile_image')->store('users', 'public');

    $data['profile_image'] = $path;
}

$user->update($data);

$user = $user->fresh();

$user->profile_image = $user->profile_image
    ? asset('storage/' . $user->profile_image)
    : null;

return response()->json([
    'success' => true,
    'message' => 'Profile updated successfully.',
    'data' => $user,
]);
}

    // Change Password
    public function changePassword(ChangePasswordRequest $request)
{
    $user = $request->user();
    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Current password is incorrect.',
        ], 400);
    }

    if (Hash::check($request->password, $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'New password must be different from the current password.',
        ], 400);
    }

    $user->update([
        'password' => Hash::make($request->password),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Password changed successfully.',
    ]);
}

    // Delete Account
    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        $user->tokens()->delete();
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully.',
        ]);
    }
}
