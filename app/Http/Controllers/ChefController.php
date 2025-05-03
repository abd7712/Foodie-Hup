<?php

namespace App\Http\Controllers;

use App\Models\Order_Item;

class ChefController extends Controller
{
    public function show_order_items()
    {
        $order_items=Order_Item::with(["order.book.table","order.book.user","product"])
            ->whereIn("status",["pending","progress"])
            ->orderBy("created_at","asc")
            ->get();

       if($order_items->isEmpty())
       {
            return response([
                "error"=>"not found"
            ],422);
       }
       
       $order_items_formatted=$order_items->map(function($order_item){
            return[
                "order_item_id"=>$order_item->id,
                "order_item_product"=>$order_item->product->name,
                "order_item_quantity"=>$order_item->quantity,
                "order_item_status"=>$order_item->status,
                "table_details"=>[
                    "table_id"=>$order_item->order->book->table->id,
                ],
                "customer_details"=>[
                    "customer_name"=>$order_item->order->book->user->name
                ],
            ];
       });

       return response([
        "order_items"=>$order_items_formatted
       ],200);

    }

    public function progress_order_item($order_item_id)
    {
        $order_item=Order_Item::find($order_item_id);

        if (!$order_item) {
            return response(["error" => "Order item not found"], 404);
        }        

        if ($order_item->status !== "pending") {
            return response(["error" => "Only pending items can be marked as in progress"], 400);
        }
        

        $order_item->update([
            "status"=>"progress"
        ]);

        return response([
            "message"=>"order item updated successfully"
        ],201);
    }

    public function ready_order_item($order_item_id)
    {
        $order_item=Order_Item::find($order_item_id);

        if (!$order_item) {
            return response(["error" => "Order item not found"], 404);
        }
        
        if ($order_item->status !== "progress") {
            return response(["error" => "Only items in progress can be marked as ready"], 400);
        }

        $order_item->update([
            "status"=>"ready"
        ]);

        return response([
            "message"=>"order item updated successfully"
        ],201);
    }

    
}
