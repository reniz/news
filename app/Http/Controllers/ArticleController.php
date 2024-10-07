<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    // Fetch articles with pagination
    public function getArticles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        $articles = Article::paginate($perPage, ['*'], 'page', $page);

        return response()->json($articles, 200);
    }

    // Search articles by keyword, date, category, and source
    public function searchArticles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'keyword' => 'nullable|string',
            'date' => 'nullable|date',
            'category' => 'nullable|string',
            'source' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $query = Article::query();

        if ($request->filled('keyword')) {
            $query->where('title', 'like', '%' . $request->input('keyword') . '%')
                  ->orWhere('content', 'like', '%' . $request->input('keyword') . '%');
        }

        if ($request->filled('date')) {
            $query->whereDate('published_at', $request->input('date'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('source')) {
            $query->where('source', $request->input('source'));
        }

        $articles = $query->paginate(10);

        return response()->json($articles, 200);
    }

    // Get details of a single article
    public function getArticleDetails($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        return response()->json($article, 200);
    }
}
?>