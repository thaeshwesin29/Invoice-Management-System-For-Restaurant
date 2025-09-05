<?php

namespace App\Http\Controllers\Frontend\Staff;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    public function index()
    {
        return view('frontend.staff.category.index');
    }

    public function getCategoryList()
    {
        $categories = Category::paginate(10);

        $categories = CategoryResource::collection($categories);

        $data = [
            'data' => $categories,
            'meta' => [
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total(),
            ],
            'links' => [
                'prev' => $categories->previousPageUrl(),
                'next' => $categories->nextPageUrl(),
            ]
        ];

        return success( $data);
    }
}
