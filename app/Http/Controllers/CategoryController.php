<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $expenses = Auth::user()->expenses()->with('category')->get();

        return Inertia::render('Expenses', [
            'expenses' => $expenses,
            'categories' => $categories,
        ]);
    }

}
