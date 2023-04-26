<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * ...
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $categories = Category::all();


        return response()->json([
            'status' => true,
            'data' => $categories
        ]);
    }

    public function store(Request $request)
    {
    }

    public function show(Category $category)
    {
    }

    public function update(Request $request, Category $category)
    {
    }

    public function destroy(Category $category)
    {
    }
}
