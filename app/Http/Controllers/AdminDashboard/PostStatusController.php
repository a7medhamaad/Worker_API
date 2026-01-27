<?php

namespace App\Http\Controllers\AdminDashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\POsts\PostStatusRequest;
use App\Models\Post;
use App\Notifications\AdminPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class PostStatusController extends Controller
{
    public function changeStatus(PostStatusRequest $request)
    {
        $post = Post::find($request->post_id);
        $post->update(

            [
                'status' => $request->status,
                'reject_reason' => $request->reject_reason
            ]
        );

        Notification::send($post->worker,new AdminPost($post->worker,$post));
        return response()->json([
            'message'=>"status Update"
        ]);
    }



}
