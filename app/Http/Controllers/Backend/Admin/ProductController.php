<?php

namespace App\Http\Controllers\Backend\Admin;

use Exception;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::orderByDesc('created_at');

            return DataTables::of($products)
                ->addColumn('category', function ($product) {
                    return optional($product->category)->name;
                })
                ->addColumn('image', function ($product) {
                    return '<img src="' . $product->image_url . '" class="object-cover w-9 h-9"/>';
                })
                ->addColumn('price', function ($invoice) {
                    return number_format($invoice->price) . ' ' . __('message.mmk');
                })
                ->addColumn('stock_quantity', function ($invoice) {
                    return number_format($invoice->stock_quantity);
                })
                ->addColumn('action', function ($product) {
                    $edit_btn = '<a href="'. route('admin.product.edit', $product->id) .'" class="btn btn-sm btn-warning m-2"><i class="fa-solid fa-pen-to-square"></i></a>';
                    $delete_btn = '<a href="#" class="btn btn-sm btn-danger text-light m-2 delete-btn" data-delete-url="' . route('admin.product.destroy', $product->id) . '"><i class="fa-solid fa-trash"></i></a>';

                    return '<div class="flex justify-evenly">
                        ' . $edit_btn . ' ' . $delete_btn . '
                    </div>';
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }

        return view('backend.admin.product.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get();

        return view('backend.admin.product.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'category_id' => 'required',
                'name' => 'required|unique:categories,name',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'price' => 'required',
                'stock_quantity' => 'required',
            ]);


            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $file_name = time() . '_' . uniqid() . '.' . str_replace(' ', '', $file->getClientOriginalName());
                $file->storeAs('public/product', $file_name);
            }

            Product::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'description' => $request->description,
                'image' => $file_name,
                'price' => $request->price,
                'stock_quantity' => $request->stock_quantity,
            ]);

            return redirect()->route('admin.product.index')->with('success', 'Product created successfully');

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
    public function edit(Product $product)
    {
        $categories = Category::get();

        return view('backend.admin.product.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        try {
            $request->validate([
                'category_id' => 'required',
                'name' => 'required|unique:products,name,' . $product->id,
                'image' => (!$product->image ? 'required' : '') . '|image|mimes:jpeg,png,jpg,gif|max:2048',
                'price' => 'required',
                'stock_quantity' => 'required',
            ]);


            $file_name = null;
            if ($request->hasFile('image')) {
                if ($product->image) {
                    if (Storage::exists('public/product/' . $product->image)) {
                        Storage::delete('public/product/' . $product->image);
                    }
                }

                $file = $request->file('image');
                $file_name = time() . '_' . uniqid() . '.' . str_replace(' ', '', $file->getClientOriginalName());
                $file->storeAs('public/product', $file_name);
            }

            $product->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'description' => $request->description,
                'image' => $file_name ? $file_name : $product->image,
                'price' => $request->price,
                'stock_quantity' => $request->stock_quantity,
            ]);

            return redirect()->route('admin.product.index')->with('success', 'Product updated successfully');

        } catch (Exception $e) {
            Log::info($e);
            return redirect()->back()->with('fail', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Product $product)
    {
        try {
            if (!$request->ajax()) {
                throw new Exception('Invalid request!');
            }

            if ($product->image) {
                if (Storage::exists('public/product/' . $product->image)) {
                    Storage::delete('public/product/' . $product->image);
                }
            }

            $product->delete();

            return successMessage('Product deleted successfully');

        } catch (Exception $e) {
            Log::info($e);
            return errorMessage($e->getMessage());
        }
    }
}
