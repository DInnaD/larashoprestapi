<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Purchase::class, 'purchase');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __invoke($id)
    {
        return response()->json(['success' => Order::findOrFail($id)]);//?

    }
    public function index()
    {
        return response()->json(['success' => Order::all()]);
    }
    public function setPurchase($order_id, $purchase_id)
    {
        $order = Order::findOrFail($order_id);
        $purchase = Purchase::findOrFail($purchase_id);
        $purchase->orders()->save($order);
        return response()->json($order->load('purchase'), 200);
    }
    public function show(Order $order)
    {
        //
        return response()->json($order->load('purchases'), 200);//polimorph???
    }    
}
