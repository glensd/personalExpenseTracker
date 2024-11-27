<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'status' => true,
            'message' => 'Success.',
            'data' => $categories
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation failed.', 'data' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $category = new Category();
        $category->name = $request->name;
        $category->save();

        return response()->json([
            'status' => true,
            'message' => 'Category added successfully.',
            'data' => $category
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation failed.', 'data' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $category = Category::findOrFail($id);

        $category->name = $request->name;
        $category->save();

        return response()->json([
            'status' => true,
            'message' => 'Category updated successfully.',
            'data' => $category
        ], Response::HTTP_CREATED);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $category->delete();
        return response()->json(['message' => 'Category deleted successfully'], Response::HTTP_OK);
    }
}
