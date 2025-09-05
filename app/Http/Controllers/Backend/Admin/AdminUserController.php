<?php

namespace App\Http\Controllers\Backend\Admin;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $admin_users = User::orderByDesc('created_at');

            return DataTables::of($admin_users)
                ->addColumn('profile_image', function ($admin_user) {
                    return '<img src="' . $admin_user->profile_image_url . '" class="object-cover w-9 h-9"/>';
                })
                ->addColumn('action', function ($admin_user) {
                    $edit_btn = '<a href="'. route('admin.admin-user.edit', $admin_user->id) .'" class="btn btn-sm btn-warning m-2"><i class="fa-solid fa-pen-to-square"></i></a>';
                    $info_btn = '<a href="'. route('admin.admin-user.show', $admin_user->id) .'" class="btn btn-sm btn-primary m-2"><i class="fa-solid fa-circle-info"></i></a>';
                    $change_password_btn = '<a href="'. route('admin.admin-user.change-password', $admin_user->id) .'" class="btn btn-sm btn-success text-light m-2"><i class="fa-solid fa-user-shield"></i></a>';
                    $delete_btn = '<a href="#" class="btn btn-sm btn-danger text-light m-2 delete-btn" data-delete-url="' . route('admin.admin-user.destroy', $admin_user->id) . '"><i class="fa-solid fa-trash"></i></a>';

                    return '<div class="flex justify-evenly">
                        ' . $edit_btn . $info_btn . $change_password_btn . $delete_btn . '
                    </div>';
                })
                ->rawColumns(['profile_image', 'action'])
                ->make(true);
        }

        return view('backend.admin.admin_user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.admin.admin_user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|unique:users,name',
                'email' => 'required|unique:users,email',
                'phone' => 'required|unique:users,phone',
                'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'password' => 'required',
                'password_confirmation' => 'required|same:password',
            ]);

            if ($request->password != $request->password_confirmation) {
                throw new Exception("Password doesn't match!");
            }

            $file_name = null;
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $file_name = time() . '_' . uniqid() . '.' . str_replace(' ', '', $file->getClientOriginalName());
                $file->storeAs('public/admin_user', $file_name);
            }

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'profile_image' => $file_name,
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('admin.admin-user.index')->with('success', 'Admin user created successfully');

        } catch (Exception $e) {
            Log::info($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $admin_user)
    {
        return view('backend.admin.admin_user.show', compact('admin_user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $admin_user)
    {
        return view('backend.admin.admin_user.edit', compact('admin_user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $admin_user)
    {
        try {
            $request->validate([
                'name' => 'required|unique:users,name,' . $admin_user->id,
                'email' => 'required|unique:users,email,' . $admin_user->id,
                'phone' => 'required|unique:users,phone,' . $admin_user->id,
                'profile_image' => (!$admin_user->profile_image ? 'required' : '') . '|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $file_name = null;
            if ($request->hasFile('profile_image')) {
                if ($admin_user->profile_image) {
                    if (Storage::exists('public/admin_user/' . $admin_user->profile_image)) {
                        Storage::delete('public/admin_user/' . $admin_user->profile_image);
                    }
                }

                $file = $request->file('profile_image');
                $file_name = time() . '_' . uniqid() . '.' . str_replace(' ', '', $file->getClientOriginalName());
                $file->storeAs('public/admin_user', $file_name);
            }

            $admin_user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'profile_image' => $file_name ? $file_name : $admin_user->profile_image,
            ]);

            return redirect()->route('admin.admin-user.index')->with('success', 'Admin user updated successfully');

        } catch (Exception $e) {
            Log::info($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $admin_user)
    {
        try {
            if (!$request->ajax()) {
                throw new Exception('Invalid request!');
            }

            if ($admin_user->profile_image) {
                if (Storage::exists('public/admin_user/' . $admin_user->profile_image)) {
                    Storage::delete('public/admin_user/' . $admin_user->profile_image);
                }
            }

            $admin_user->delete();

            return successMessage('Admin user deleted successfully');

        } catch (Exception $e) {
            Log::info($e);
            return errorMessage($e->getMessage());
        }
    }

    public function changePassword(User $admin_user)
    {
        return view('backend.admin.admin_user.change_password', compact('admin_user'));
    }

    public function updatePassword(Request $request, User $admin_user)
    {
        try {
            $request->validate([
                'password' => 'required',
                'password_confirmation' => 'required|same:password',
            ]);

            if ($request->password != $request->password_confirmation) {
                throw new Exception("Password doesn't match!");
            }

            $admin_user->update([
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('admin.admin-user.index')->with('success', 'Admin user password updated successfully');

        } catch (Exception $e) {
            Log::info($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
