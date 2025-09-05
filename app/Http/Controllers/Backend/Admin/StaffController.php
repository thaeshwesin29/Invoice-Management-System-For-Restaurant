<?php

namespace App\Http\Controllers\Backend\Admin;

use Exception;
use App\Models\Staff;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $staffs = Staff::orderByDesc('created_at');

            return DataTables::of($staffs)
                ->addColumn('profile_image', function ($staff) {
                    return '<img src="' . $staff->profile_image_url . '" class="object-cover w-9 h-9"/>';
                })
                ->addColumn('action', function ($staff) {
                    $edit_btn = '<a href="'. route('admin.staff.edit', $staff->id) .'" class="btn btn-sm btn-warning m-2"><i class="fa-solid fa-pen-to-square"></i></a>';
                    $info_btn = '<a href="'. route('admin.staff.show', $staff->id) .'" class="btn btn-sm btn-primary m-2"><i class="fa-solid fa-circle-info"></i></a>';
                    $change_password_btn = '<a href="'. route('admin.staff.change-password', $staff->id) .'" class="btn btn-sm btn-success text-light m-2"><i class="fa-solid fa-user-shield"></i></a>';
                    $delete_btn = '<a href="#" class="btn btn-sm btn-danger text-light m-2 delete-btn" data-delete-url="' . route('admin.staff.destroy', $staff->id) . '"><i class="fa-solid fa-trash"></i></a>';

                    return '<div class="flex justify-evenly">
                        ' . $edit_btn . $info_btn . $change_password_btn . $delete_btn . '
                    </div>';
                })
                ->rawColumns(['profile_image', 'action'])
                ->make(true);
        }

        return view('backend.admin.staff.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.admin.staff.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|unique:staff,name',
                'email' => 'required|unique:staff,email',
                'phone' => 'required|unique:staff,phone',
                'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'nrc' => 'required|unique:staff,nrc',
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
                $file->storeAs('public/staff', $file_name);
            }

            Staff::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'profile_image' => $file_name,
                'nrc' => $request->nrc,
                'address' => $request->address,
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('admin.staff.index')->with('success', 'Staff created successfully');

        } catch (Exception $e) {
            Log::info($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        return view('backend.admin.staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {
        return view('backend.admin.staff.edit', compact('staff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Staff $staff)
    {
        try {
            $request->validate([
                'name' => 'required|unique:staff,name,' . $staff->id,
                'email' => 'required|unique:staff,email,' . $staff->id,
                'phone' => 'required|unique:staff,phone,' . $staff->id,
                'profile_image' => (!$staff->profile_image ? 'required' : '') . '|image|mimes:jpeg,png,jpg,gif|max:2048',
                'nrc' => 'required|unique:staff,nrc,' . $staff->id,
            ]);

            $file_name = null;
            if ($request->hasFile('profile_image')) {
                if ($staff->profile_image) {
                    if (Storage::exists('public/staff/' . $staff->profile_image)) {
                        Storage::delete('public/staff/' . $staff->profile_image);
                    }
                }

                $file = $request->file('profile_image');
                $file_name = time() . '_' . uniqid() . '.' . str_replace(' ', '', $file->getClientOriginalName());
                $file->storeAs('public/staff', $file_name);
            }

            $staff->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'nrc' => $request->nrc,
                'profile_image' => $file_name ? $file_name : $staff->profile_image,
                'address' => $request->address,
            ]);

            return redirect()->route('admin.staff.index')->with('success', 'Staff updated successfully');

        } catch (Exception $e) {
            Log::info($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Staff $staff)
    {
        try {
            if (!$request->ajax()) {
                throw new Exception('Invalid request!');
            }

            if ($staff->profile_image) {
                if (Storage::exists('public/staff/' . $staff->profile_image)) {
                    Storage::delete('public/staff/' . $staff->profile_image);
                }
            }

            $staff->delete();

            return successMessage('Staff deleted successfully');

        } catch (Exception $e) {
            Log::info($e);
            return errorMessage($e->getMessage());
        }
    }

    public function changePassword(Staff $staff)
    {
        return view('backend.admin.staff.change_password', compact('staff'));
    }

    public function updatePassword(Request $request, Staff $staff)
    {
        try {
            $request->validate([
                'password' => 'required',
                'password_confirmation' => 'required|same:password',
            ]);

            if ($request->password != $request->password_confirmation) {
                throw new Exception("Password doesn't match!");
            }

            $staff->update([
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('admin.staff.index')->with('success', 'Staff password updated successfully');

        } catch (Exception $e) {
            Log::info($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
