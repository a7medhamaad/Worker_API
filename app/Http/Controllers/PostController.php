<?php

namespace App\Http\Controllers;

use App\Filter\PostFilter;
use App\Http\Requests\StoringPostRequest;
use App\Models\Post;
use App\Services\PostService\StoringPostService;
use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PostController extends Controller
{
    public function store(StoringPostRequest $request)
    {
        return (new StoringPostService())->store($request);
    }


    public function index()
    {
        $posts = Post::all();
        return response()->json([
            "posts" => $posts,
        ]);
    }
    // public function approved()
    // {
    //     $posts = QueryBuilder::for(Post::class)
    //         ->allowedFilters((new PostFilter())->filter())
    //         ->with('worker:id,name')
    //         ->where('status', 'approved')
    //         ->get(['id', 'price', 'content', 'worker_id']);
    //     return response()->json([
    //         "posts" => $posts,
    //     ]);
    // }
    // public function rejected()
    // {
    //     $posts = QueryBuilder::for(Post::class)
    //         ->allowedFilters((new PostFilter())->filter())
    //         ->with('worker:id,name')
    //         ->where('status', 'rejected')
    //         ->get(['id', 'price', 'content', 'worker_id']);
    //     return response()->json([
    //         "posts" => $posts,
    //     ]);
    // }
    // public function pending()
    // {
    //     $posts = QueryBuilder::for(Post::class)
    //         ->allowedFilters((new PostFilter())->filter())
    //         ->with('worker:id,name')
    //         ->where('status', 'pending')
    //         ->get(['id', 'price', 'content', 'worker_id']);
    //     return response()->json([
    //         "posts" => $posts,
    //     ]);
    // }



    public function byStatus(string $status)
    {
        if (!in_array($status, ['approved', 'rejected', 'pending'])) {
            return response()->json([
                'message' => 'Invalid status'
            ], 422);
        }

        $posts = QueryBuilder::for(Post::class)
            ->where('status', $status)
            ->allowedFilters((new PostFilter())->filter())
            ->with('worker:id,name')
            ->get(['id', 'price', 'content', 'worker_id']);

        return response()->json([
            'posts' => $posts,
        ]);
    }
}
