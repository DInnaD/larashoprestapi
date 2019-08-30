<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
     if(Auth::check())
    	{
    		$user_id = \Auth::user()->id;
    		$books = \App\Http\Resources\BooksCollection::make(Book::where('user_id', $user_id)->get());
    		$magazines = \App\Http\Resources\MagazinesCollection::make(Magazine::where('user_id', $user_id)->get());
	        $users = \App\Http\Resources\UserCollection::make(User::all());
	        $purchases = \App\Http\Resources\PurchaseCollection::make(Purchase::all());
	        $orders = \App\Http\Resources\OrderCollection::make(Order::all());
    	}
    		
		$users = \App\Http\Resources\UserCollection::make(User::all());
        $books = \App\Http\Resources\BooksCollection::make(Book::paginate(10));
        $magazines = \App\Http\Resources\MagazinesCollection::make(Magazine::paginate(10));
        $purchases = \App\Http\Resources\PurchaseCollection::make(Purchase::all());
	    $orders = \App\Http\Resources\OrderCollection::make(Order::all());

        return response()->json(['success' => User::all()], ['success' => Book::all()], ['success' => Magazine::all()],['success' => Purchase::all()], ['success' => Order::all()]);
	 //return view('homes.index')->with('books', $books)->with('magazins', $magazins)->with('purchases', $purchases)->with('users', $users)->with('orders', $order);
}
