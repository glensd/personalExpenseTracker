<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'nullable|exists:categories,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'data' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $query = Expense::where('user_id', Auth::id());

        // Apply filters
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('expense_date', [$request->start_date, $request->end_date]);
        }

        $expenses = $query->with('category')->get();

        return response()->json([
            'status' => true,
            'message' => 'Expenses retrieved successfully.',
            'data' => $expenses
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $expense = Expense::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'description' => $request->description,
            'expense_date' => $request->expense_date,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Expense added successfully.',
            'data' => $expense->load('category'), // Include related category for the response
        ], 201);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
        ]);
        $category = Category::withTrashed()->find($request->category_id);

        if (!$category || $category->trashed()) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found or has been deleted.',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        }
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation failed.', 'data' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $expense = Expense::findOrFail($id);

        if ($expense->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $expense->category_id = $request->category_id;
        $expense->amount = $request->amount;
        $expense->description = $request->description;
        $expense->expense_date = $request->expense_date;
        $expense->save();
        return response()->json([
            'status' => true,
            'message' => 'Expenses updated successfully.',
            'data' => $expense
        ], Response::HTTP_CREATED);
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        if ($expense->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $expense->delete();
        return response()->json(['message' => 'Expense deleted successfully'], Response::HTTP_OK);
    }

    public function summary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'data' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $expenses = Expense::where('user_id', Auth::id())
        ->whereBetween('expense_date', [$request->start_date, $request->end_date])
        ->join('categories', 'categories.id', '=', 'expenses.category_id')
        ->select('categories.name as category_name', DB::raw('SUM(expenses.amount) as total_amount'))
            ->groupBy('categories.name')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Summary retrieved successfully.',
            'data' => $expenses
        ], Response::HTTP_OK);
    }

}

