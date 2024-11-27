<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Auth::user()->expenses()->with('category')->get();
        return response()->json([
            'status' => true,
            'message' => 'Success.',
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

        $expense = new Expense();
        $expense->user_id = Auth::id();
        $expense->category_id = $request->category_id;
        $expense->amount = $request->amount;
        $expense->description = $request->description;
        $expense->expense_date = $request->expense_date;
        $expense->save();
        return response()->json([
            'status' => true,
            'message' => 'Expenses set successfully.',
            'data' => $expense
        ], Response::HTTP_CREATED);
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

