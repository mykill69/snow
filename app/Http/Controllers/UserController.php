<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Office;
use App\Models\TicketDtl;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
     // Method to view all users
    public function userView()
    {

        // Fetch all users
        $users = User::all();
        // $userCount = User::count();
       

        $offices = Office::orderBy('office_name', 'asc')->get();
        
        // Return the view with users data
        return view('pages.user', compact('users','offices'));
    }


    public function addUser(Request $request)
{
    // Validate the request data with custom messages
    $request->validate([
        'email' => 'required|string|email|max:255|unique:users,email',
        'fname' => 'required|string|max:255',
        'mname' => 'nullable|string|max:255',
        'lname' => 'required|string|max:255',
        'department' => 'required|string|max:255',
        'role' => 'required|string|max:255',
        'password' => 'required|string|min:8',
        'about' => 'nullable|array',
        'about.*' => 'string|max:255',
        'address' => 'nullable|string|max:255',
        'mobile_no' => 'nullable|string|max:20',
    ], [
        'email.unique' => 'This email is already taken. Please choose another one.',
    ]);

    // Create the new user
    $password = Hash::make($request->input('password'));

    User::create([
        'email' => $request->email,
        'fname' => $request->fname,
        'mname' => $request->mname,
        'lname' => $request->lname,
        'department' => $request->department,
        'role' => $request->role,
        'password' => $password,
        'about' => is_array($request->about) ? implode('/ ', $request->about) : null,
        'address' => $request->address,
        'mobile_no' => $request->mobile_no,
    ]);

    return redirect()->back()->with('success', 'User added successfully.');
}

public function userEdit($id)
{
    $user = User::findOrFail($id);
    $offices = Office::all(); // assuming you have an Office model for the department select
    return view('pages.editUser', compact('user', 'offices'));
}

// public function userUpdate(Request $request, $id)
// {
//     $request->validate([
//         'email' => 'required|string|email|max:255|unique:users,email,' . $id,
//         'fname' => 'required|string|max:255',
//         'mname' => 'nullable|string|max:255',
//         'lname' => 'required|string|max:255',
//         'department' => 'required|string|max:255',
//         'role' => 'required|string|max:255',
//         'mobile_no' => 'nullable|string|max:20',
//         'address' => 'nullable|string|max:255',
//         'about' => 'nullable|array',
//     ]);

//     $user = User::findOrFail($id);
//     $user->email = $request->email;
//     $user->fname = $request->fname;
//     $user->mname = $request->mname;
//     $user->lname = $request->lname;
//     $user->department = $request->department;
//     $user->role = $request->role;
//     $user->mobile_no = $request->mobile_no;
//     $user->address = $request->address;
//     $user->about = is_array($request->about) ? implode(' / ', $request->about) : null;

//     // Optional: update password if provided
//     if ($request->filled('password')) {
//         $user->password = Hash::make($request->password);
//     }

//     $user->save();

//     return redirect()->route('userView', $id)->with('success', 'User updated successfully.');
// }


public function userUpdate(Request $request, $id)
{
    $request->validate([
        'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        'fname' => 'required|string|max:255',
        'mname' => 'nullable|string|max:255',
        'lname' => 'required|string|max:255',
        'department' => 'required|string|max:255',
        'role' => 'required|string|max:255',
        'mobile_no' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:255',
        'about' => 'nullable|array',
        'password' => 'nullable|string|min:6|confirmed',
        'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif',
    ]);

    $user = User::findOrFail($id);
    $user->email = $request->email;
    $user->fname = $request->fname;
    $user->mname = $request->mname;
    $user->lname = $request->lname;
    $user->department = $request->department;
    $user->role = $request->role;
    $user->mobile_no = $request->mobile_no;
    $user->address = $request->address;
    $user->about = is_array($request->about) ? implode(' / ', $request->about) : null;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    // ✅ Handle profile picture upload if provided
    if ($request->hasFile('profile_pic')) {
        $profile_pic = $request->file('profile_pic');
        $filename = time() . '_' . $profile_pic->getClientOriginalName();
        $profile_pic->storeAs('public/profile_pics', $filename);
        $user->profile_pic = 'profile_pics/' . $filename;
    }

    $user->save();

    return redirect()->route('userView', $id)->with('success', 'User updated successfully.');
}


public function updateProfilePic(Request $request, $id)
{
    $request->validate([
        'profile_pic' => 'required|image|mimes:jpeg,png,jpg,gif',
    ]);

    $user = User::findOrFail($id);
    $currentUser = auth()->user();

    if ($request->hasFile('profile_pic')) {
        $profile_pic = $request->file('profile_pic');
        $extension = $profile_pic->getClientOriginalExtension();
        $filename = strtolower($currentUser->fname . '.' . $currentUser->lname . '_profilePic.' . $extension);

        // Define manual destination path (inside public/)
        $destinationPath = public_path('template/profile_pics');

        // Ensure the directory exists
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Delete old profile picture if it exists
        $oldPath = $destinationPath . '/' . $user->profile_pic;
        if ($user->profile_pic && file_exists($oldPath)) {
            unlink($oldPath);
        }

        // Move the uploaded file to public/template/profile_pics/
        $profile_pic->move($destinationPath, $filename);

        // Save only the filename
        $user->profile_pic = 'template/profile_pics/' . $filename;
        $user->save();

        // Refresh session if current user
        if ($id === auth()->id()) {
            auth()->setUser($user);
        }
    }

    return back()->with('success', 'Profile picture updated successfully.');
}


}
