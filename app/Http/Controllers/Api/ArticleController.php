<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    // Get all articles with filters
    public function index(Request $request)
    {
        $query = Article::query();

        // Apply filters
        if ($request->has('search')) {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('source_id')) {
            $query->where('source_id', $request->source_id);
        }

        if ($request->has('date')) {
            $query->whereDate('published_at', $request->date);
        }

        // Paginate results
        return response()->json($query->latest()->paginate(10));
    }

    // Get single article by ID
    public function show($id)
    {
        $article = Article::findOrFail($id);
        return response()->json($article);
    }
}
