<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoringPostRequest;
use App\Models\Post;
use App\Services\PostService\StoringPostService;
use Illuminate\Http\Request;

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
    public function approved()
    {
        $posts = Post::with('worker:id,name')->where('status', 'approved')->get()->makeHidden('status');
        return response()->json([
            "posts" => $posts,
        ]);
    }
    public function rejected()
    {
        $posts = Post::with('worker:id,name')->where('status', 'rejected')->get()->makeHidden('status');;
        return response()->json([
            "posts" => $posts,
        ]);
    }
    public function pending()
    {
        $posts = Post::with('worker:id,name')->where('status', 'pending')->get()->makeHidden('status');;
        return response()->json([
            "posts" => $posts,
        ]);
    }
}
