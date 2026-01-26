<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoringPostRequest;
use App\Models\Post;
use App\Services\PostService\StoringPostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(StoringPostRequest $request) {
        return (new StoringPostService())->store($request);
    }
}
