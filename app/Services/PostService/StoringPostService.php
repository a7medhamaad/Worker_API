<?php

namespace App\Services\PostService;

use App\Models\Admin;
use App\Models\Post;
use App\Models\PostPhoto;
use App\Notifications\AdminPost;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class StoringPostService
{
    protected $model;
    function __construct()
    {
        $this->model = new Post();
    }
    public function storePost($data)
    {
        $data = $data->except('photos');
        $data['worker_id'] = auth()->guard('worker')->id();
        $post = Post::create($data);
        return $post;
    }


    public function storePostPhotos($request, $postid): void
    {
        foreach ($request->file('photos') as $photo) {
            PostPhoto::create([
                'post_id' => $postid,
                'photo' => $photo->store('posts', 'public'),
            ]);
        }
    }

    function sendAdminNotification($post)
    {
        $admins=Admin::get();
        Notification::send($admins, new AdminPost(auth()->guard('worker')->user(),$post));
    }


    public function store($request)
    {
        try {
            DB::beginTransaction();
            $post = $this->storePost($request);
            if ($request->hasFile('photos')) {
                $this->storePostPhotos($request, $post->id);
            }

            $this->sendAdminNotification($post);
            DB::commit();

            return response()->json([
                "message" => "photo has been Created Successfully"
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
