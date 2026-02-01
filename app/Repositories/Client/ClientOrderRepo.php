<?php
namespace App\Repositories\Client;

use App\Interfaces\Client\CrudRepoInterface;
use App\Models\ClientOrder;

class ClientOrderRepo implements CrudRepoInterface
{
    public function store($request)
    {
        $clientId = auth()->guard('client')->id();
        if (ClientOrder::where('client_id', $clientId)->where('post_id', $request->post_id)->exists()) {
            return response()->json([
                "message" => "Duplicate Order Request",
            ], 406);
        }
        $data = $request->all();
        $data['client_id'] = auth()->guard('client')->id();
        $order = ClientOrder::create($data);

        return response()->json([
            "message" => "order Created"
        ]);
    }
}
