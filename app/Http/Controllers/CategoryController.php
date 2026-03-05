<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // GET /api/categories
    public function index(): JsonResponse
    {
        $categories = Category::withCount('jobs')
                               ->orderBy('order')
                               ->orderBy('name')
                               ->get();

        return response()->json([
            'success' => true,
            'data'    => $categories,
        ]);
    }

    // POST /api/categories  (admin)
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:100|unique:categories,name',
            'icon'  => 'nullable|string|max:10',
            'color' => 'nullable|string|max:100',
            'order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $category = Category::create([
            'name'  => $request->name,
            'slug'  => Str::slug($request->name),
            'icon'  => $request->icon  ?? '💼',
            'color' => $request->color ?? 'bg-brand-50 text-brand-700',
            'order' => $request->order ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category created.',
            'data'    => $category,
        ], 201);
    }

    // PUT /api/categories/{category}  (admin)
    public function update(Request $request, Category $category): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:100|unique:categories,name,' . $category->id,
            'icon'  => 'nullable|string|max:10',
            'color' => 'nullable|string|max:100',
            'order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $category->update([
            'name'  => $request->name,
            'slug'  => Str::slug($request->name),
            'icon'  => $request->icon  ?? $category->icon,
            'color' => $request->color ?? $category->color,
            'order' => $request->order ?? $category->order,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category updated.',
            'data'    => $category,
        ]);
    }

    // DELETE /api/categories/{category}  (admin)
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted.',
        ]);
    }
}