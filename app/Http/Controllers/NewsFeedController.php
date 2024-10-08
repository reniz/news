<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\UserPreference;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class NewsFeedController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/user/personalized-feed",
     *     summary="Get personalized news feed",
     *     tags={"News Feed"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function getPersonalizedFeed(Request $request)
{
    $user = Auth::user();
    $cacheKey = 'user_' . $user->id . '_personalized_feed';

    // Cache the response for 60 minutes
    $articles = Cache::remember($cacheKey, 60, function () use ($user) {
        $preferences = $user->preferences;

        $articlesQuery = Article::query();

        if ($preferences->preferred_sources) {
            $articlesQuery->whereIn('source', explode(',', $preferences->preferred_sources));
        }

        if ($preferences->preferred_categories) {
            $articlesQuery->whereIn('category', explode(',', $preferences->preferred_categories));
        }

        if ($preferences->preferred_authors) {
            $articlesQuery->whereIn('author', explode(',', $preferences->preferred_authors));
        }

        return $articlesQuery->paginate(10);
    });

    return response()->json($articles);
}

    /**
     * @OA\Post(
     *     path="/api/user/preferences",
     *     summary="Set user preferences",
     *     tags={"User Preferences"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="preferred_sources", type="string", example="The Guardian,New York Times"),
     *             @OA\Property(property="preferred_categories", type="string", example="Technology,World News"),
     *             @OA\Property(property="preferred_authors", type="string", example="John Doe,Jane Smith")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Preferences updated successfully."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function setPreferences(Request $request)
    {
        $user = Auth::user();

        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'preferred_sources' => 'nullable|string',
            'preferred_categories' => 'nullable|string',
            'preferred_authors' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update or create user preferences
        $user->preferences()->updateOrCreate(
            ['user_id' => $user->id],
            $request->only(['preferred_sources', 'preferred_categories', 'preferred_authors'])
        );

        return response()->json(['message' => 'Preferences updated successfully.']);
    }

    /**
     * @OA\Get(
     *     path="/api/user/preferences",
     *     summary="Get user preferences",
     *     tags={"User Preferences"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function getPreferences()
    {
        $user = Auth::user();
        $preferences = $user->preferences;

        return response()->json($preferences);
    }
}
