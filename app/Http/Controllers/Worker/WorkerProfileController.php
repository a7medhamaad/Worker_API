<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Http\Requests\Worker\UpdatingProfileRequest;
use App\Models\Post;
use App\Models\Worker;
use App\Models\WorkerReview;
use App\Services\WorkerService\UpdatingProfileService;
use Illuminate\Http\Request;

class WorkerProfileController extends Controller
{
    public function userProfile()
    {
        $workerId = auth()->guard('worker')->id();
        $worker = Worker::with('posts.reviews')->find($workerId)->makeHidden('verified_at', 'verification_token', 'status');
        $reviews = WorkerReview::whereIn("post_id", $worker->posts()->pluck('id'));
        $rate = round($reviews->sum('rate') / $reviews->count(), 1);

        return response()->json([
            "data" => array_merge($worker->toArray(), ["rate" => $rate]),
        ]);

        // return response()->json(auth()->Guard('worker')->user());
    }

    public function edit()
    {
        return response()->json([
            "worker" => Worker::find(auth()->guard('worker')->id())->makeHidden('verified_at', 'verification_token', 'status')
        ]);
    }

    public function update(UpdatingProfileRequest $request)
    {
        return (new UpdatingProfileService())->update($request);
    }


    public function delete()
    {

        Post::where('worker_id', auth()->guard('worker')->id())->delete();

        return response()->json([
            "message" => "Post Deleted",
        ]);
    }
}
