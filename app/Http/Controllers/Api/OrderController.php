<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //user: create new order
    public function create_order(Request $request)
    {
        $request->validate([
            'order_items' => 'required|array',
            'order_items.*.product_id' => 'required|integer|exists:products,id',
            'order_items.*.quantity' => 'required|integer|min:1',
            'restaurant_id' => 'required|integer|exists:users,id',
            'shipping_cost' => 'required|integer'
        ]);

        $total_price = 0;
        foreach ($request->order_items as $item) {
            $product = Product::find($item['product_id']);
            $total_price += $product->price * $item['quantity'];
        }

        $total_bill = $total_price + $request->shipping_cost;

        $user = $request->user();
        $data = $request->all();
        $data['user_id'] = $user->id;
        $data['shipping_address'] = $user->address;
        $data['shipping_latlong'] = $user->latlong;
        $data['status'] = 'pending';
        $data['total_price'] = $total_price;
        $data['total_bill'] = $total_bill;

        $order = Order::create($data);

        foreach ($request->order_items as $item) {
            $product = Product::find($item['product_id']);
            $order_item = new OrderItem([
                "product_id" => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price
            ]);
            $order->order_items()->save($order_item);
        }

        return response()->json([
           'status' =>'success',
           'message' => 'Order created successfully',
            'data' => $order
        ]);
    }

    //user: update purchase status
    public function update_purchase_status(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,completed,cancelled'
        ]);

        $order = Order::find($id);
        if(!$order){
            return response()->json([
               'status' => 'failed',
               'message' => 'Order not found'
            ]);
        }
        $order->status = $request->status;
        $order->save();

        return response() ->json([
            'status' =>'success',
           'message' => 'Order status updated successfully',
           'data' => $order
        ]);
    }

    //user: order history
    public function order_history(Request $request)
    {
        $user = $request->user();
        $orders = Order::where('user_id', $user->id)->get();
        return response() ->json([
            'status' =>'success',
           'message' => 'Order history fetched successfully',
           'data' => $orders
        ]);
    }

    //user: cancel order
    public function cancel_order($id)
    {
        $order = Order::find($id);
        if(!$order){
            return response()->json([
               'status' => 'failed',
               'message' => 'Order not found'
            ]);
        }
        $order->status = 'cancelled';
        $order->save();
        return response() ->json([
            'status' =>'success',
           'message' => 'Order cancelled successfully',
           'data' => $order
        ]);
    }

    //get order by status for restaurant
    public function get_order_by_status(Request $request)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,completed,cancelled'
        ]);
        $user = $request->user();
        $orders = Order::where('restaurant_id', $user->id)->where('status', $request->status)->get();
        return response() ->json([
            'status' =>'success',
           'message' => 'Get all order by status successfully',
           'data' => $orders
        ]);
    }

    //update order status for restaurant
    public function update_order_status(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,completed,cancelled,ready_for_delivery,prepared'
        ]);
        $order = Order::find($id);
        if(!$order){
            return response()->json([
               'status' => 'failed',
               'message' => 'Order not found'
            ]);
        }
        $order->status = $request->status;
        $order->save();
        return response() ->json([
            'status' =>'success',
           'message' => 'Order status updated successfully',
           'data' => $order
        ]);
    }

    //driver: get order by status
    public function get_order_by_status_for_driver(Request $request)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,completed,cancelled,on_delivery,delivered'
        ]);
        $user = $request->user();
        $orders = Order::where('driver_id', $user->id)->where('status', $request->status)->get();
        return response() ->json([
            'status' =>'success',
           'message' => 'Get all order by status successfully',
           'data' => $orders
        ]);
    }

    //driver: get order status ready_for_delivery
    public function get_order_ready_for_delivery(Request $request)
    {
        $orders = Order::with('restaurant')->where('status', $request->status)->get();
        return response() ->json([
            'status' =>'success',
           'message' => 'Get all order by status ready_for_delivery successfully',
           'data' => $orders
        ]);
    }

    //driver: update order status
    public function update_order_status_for_driver(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,completed,cancelled,on_delivery,delivered'
        ]);
        $order = Order::find($id);
        if(!$order){
            return response()->json([
               'status' => 'failed',
               'message' => 'Order not found'
            ]);
        }
        $order->status = $request->status;
        $order->save();
        return response() ->json([
            'status' =>'success',
           'message' => 'Order status updated successfully',
           'data' => $order
        ]);
    }
}
