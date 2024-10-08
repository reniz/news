<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Validator;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="News Aggregator API",
 *      description="This is the documentation for the News Aggregator API",
 *
 *      @OA\Contact(
 *          email="admin@admin.com"
 *      ),
 *
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 *      
 *     
 * )
 * 
 *   @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT"
 * )
 */

class ArticleController extends Controller
{

     /**
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="Demo API Server"
     * )
     */
    /**
     * @OA\Get(
     *     path="/api/articles",
     *     summary="Fetch articles with pagination",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of articles per page",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/articles/search",
     *     summary="Search articles by keyword, date, category, and source",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="Keyword to search in articles",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Publication date of the article",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Category of the article",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="source",
     *         in="query",
     *         description="Source of the article",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
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
            $query->whereFullText(['title', 'content'], $request->input('keyword'));
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

    /**
     * @OA\Get(
     *     path="/api/articles/{id}",
     *     summary="Get details of a single article",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the article",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found"
     *     )
     * )
     */
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