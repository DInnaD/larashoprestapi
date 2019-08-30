<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrdersController extends Controller
{
	public function __construct()
    {
        $this->authorizeResource(Order::class, 'order');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = \App\Http\Resources\OrdersCollection::make(Order::all());
        return response()->json($orders,200);
    }
}
