<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\ClientOrderRequest;
use App\Interfaces\Client\CrudRepoInterface;
use App\Models\ClientOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientOrderController extends Controller
{


    protected $CrudRepo;
    public function __construct(CrudRepoInterface $crudRepo)
    {
        $this->CrudRepo = $crudRepo;
    }
    public function addOrder(ClientOrderRequest $request)
    {
        return $this->CrudRepo->store($request);
    }

    public function workerPendingOrder()
    {
        $orders = ClientOrder::with('post', 'client')->whereStatus('pending')
            ->whereHas('post', function ($query) {
                $query->where('worker_id', auth()->guard('worker')->id());
            })->get();
        return response()->json([
            "orders" => $orders,
        ]);
    }
    public function workerRejectOrder()
    {
        $orders = ClientOrder::with('post', 'client')->whereStatus('rejected')
            ->whereHas('post', function ($query) {
                $query->where('worker_id', auth()->guard('worker')->id());
            })->get();
        return response()->json([
            "orders" => $orders,
        ]);
    }
    public function workerApproveOrder()
    {
        $orders = ClientOrder::with('post', 'client')->whereStatus('approved')
            ->whereHas('post', function ($query) {
                $query->where('worker_id', auth()->guard('worker')->id());
            })->get();
        return response()->json([
            "orders" => $orders,
        ]);
    }

    public function update(Request $request, $id)
    {
        $order = ClientOrder::where('id', $id)
            ->whereHas('post', function ($query) {
                $query->where('worker_id', Auth::id());
            })
            ->first();

        if (!$order) {
            return response()->json([
                'message' => 'You are not allowed to update this order'
            ], 404);
        }
        $order->setAttribute('status', $request->status)->save();

        return response()->json([
            "message" => "Updated",
        ]);
    }
}
