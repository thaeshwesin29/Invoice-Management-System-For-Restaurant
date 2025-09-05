<?php

namespace App\Http\Controllers\Backend\Admin;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::orderByDesc('created_at');

            return DataTables::of($categories)
                ->addColumn('image', function ($category) {
                    return '<img src="' . $category->image_url . '" class="object-cover w-9 h-9"/>';
                })
                ->addColumn('action', function ($category) {
                    $edit_btn = '<a href="'. route('admin.category.edit', $category->id) .'" class="btn btn-sm btn-warning m-1"><i class="fa-solid fa-pen-to-square"></i></a>';
                    $delete_btn = '<a href="#" class="btn btn-sm btn-danger text-light delete-btn m-1" data-delete-url="' . route('admin.category.destroy', $category->id) . '"><i class="fa-solid fa-trash"></i></a>';

                    return '<div class="flex justify-evenly">
                        ' . $edit_btn . $delete_btn . '
                    </div>';
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }

        return view('backend.admin.category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|unique:categories,name',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);


            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $file_name = time() . '_' . uniqid() . '.' . str_replace(' ', '', $file->getClientOriginalName());
                $file->storeAs('public/category', $file_name);
            }

            Category::create([
                'name' => $request->name,
                'image' => $file_name,
            ]);

            return redirect()->route('admin.category.index')->with('success', 'Category created successfully');

        } catch (Exception $e) {
            Log::info($e);
            return redirect()->back()->with('fail', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('backend.admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        try {
            $request->validate([
                'name' => 'required|unique:categories,name,' . $category->id,
                'image' => (!$category->image ? 'required' : '') . '|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);


            $file_name = null;
            if ($request->hasFile('image')) {
                if ($category->image) {
                    if (Storage::exists('public/category/' . $category->image)) {
                        Storage::delete('public/category/' . $category->image);
                    }
                }

                $file = $request->file('image');
                $file_name = time() . '_' . uniqid() . '.' . str_replace(' ', '', $file->getClientOriginalName());
                $file->storeAs('public/category', $file_name);
            }

            $category->update([
                'name' => $request->name,
                'image' => $file_name ? $file_name : $category->image,
            ]);

            return redirect()->route('admin.category.index')->with('success', 'Category updated successfully');

        } catch (Exception $e) {
            Log::info($e);
            return redirect()->back()->with('fail', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Category $category)
    {
        try {
            if (!$request->ajax()) {
                throw new Exception('Invalid request!');
            }

            if ($category->image) {
                if (Storage::exists('public/category/' . $category->image)) {
                    Storage::delete('public/category/' . $category->image);
                }
            }

            $category->delete();

            return successMessage('Category deleted successfully');

        } catch (Exception $e) {
            Log::info($e);
            return errorMessage($e->getMessage());
        }
    }
}
