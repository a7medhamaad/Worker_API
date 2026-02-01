<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Http\Requests\Worker\WorkerReviewRequest;
use App\Http\Resources\Worker\WorkerReviewResource;
use App\Models\WorkerReview;
use Illuminate\Http\Request;

class WorkerReviewController extends Controller
{
    public function reviewStore(WorkerReviewRequest $request)
    {
        $data = $request->validated();
        $data['client_id'] = auth()->guard('client')->id();

        $reviews = WorkerReview::create($data);
        return response()->json([
            "data" => $reviews
        ]);
    }

    public function postRate($postId)
    {
        $reviews = WorkerReview::wherePostId($postId);
        $averge = $reviews->sum('rate') / $reviews->count();

        return response()->json([
            "total_rate" => round($averge, 1),
            "data" => WorkerReviewResource::collection($reviews->get()),
        ]);
    }
}
