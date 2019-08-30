<?php

namespace App\Http\Controllers\Admin;

use Auth; 
use App\Purchase;
use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchasesController extends Controller
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
    public function index(Request $request)
    {
        $purchases = \App\Http\Resources\PurchasesCollection::make(Purchase::where('status_bought', '==', '1')->where('status_paid', '==', '1')->get());
        return response()->json($purchases,200);
    }
    
    public function toggle($id)
    {
        $purchase = Purchase::find($id);
        $purchase->toggleStatus();

        return response()->json($purchase,200);
    }

    public function indexDayBefore()
    {
        $day = new \DateTime('now');
        $day = $day->sub(new \DateInterval('P1D'));
        //dd(time() - 1 * 24 *60 * 60);
        $purchases = \App\Http\Resources\PurchasesCollection::make(Purchase::where('created_at', '>', $day)->get());

        
        foreach ($purchases as $purchase) 
        {
            //$purchase->book->getSum($summa)
            //$purchase->book->getQuantityTotal($qty)
            //$purchase->book->getQuantity($sumQty)
            //$purchase->magazine->getQuantity($sumQ ty)
            //Top-5 Books
            $purchase->book = Purchase::where('qty')->orderBy('qty','desc')->take(5)->get();
            //Top-5 Magazins
            $purchase->magazine = Purchase::where('qty')->orderBy('qty','desc')->take(5)->get();

        }



        return response()->json($purchases,200);
    }

    public function indexWeekBefore($summa, $sumQty, $sumQty_m)
    {

        $day = new \DateTime('now');
        $day = $day->sub(new \DateInterval('P7D'));

        $purchases = \App\Http\Resources\PurchasesCollection::make(Purchase::where('created_at', '>', $day)->get());

        foreach ($purchases as $purchase) 
        {
            //$purchase->getSum($summa)
            //$purchase->getQuantitySum($sumQty, $sumQty_m)
            //$purchase->getQuantity($sumQty)
            //$purchase->getQuantity_m($sumQty_m)
            //Top-5 Books
            $purchase->book = Purchase::where('qty')->orderBy('qty','desc')->take(5)->get();
            //Top-5 Magazins
            $purchase->magazine = Purchase::where('qty')->orderBy('qty','desc')->take(5)->get();

        }



        return response()->json($purchases,200);
    }

    public function indexMonthBefore()
    {
        $day = new \DateTime('now');
        $day = $day->sub(new \DateInterval('P1M0D'));

        $purchases = \App\Http\Resources\PurchasesCollection::make(Purchase::where('created_at', '>', time() - 30 * 24 *60 * 60)->get());

        foreach ($purchases as $purchase) 
        {
            //$purchase->getSum($summa)
            //$purchase->getQuantitySum($sumQty, $sumQty_m)
            //$purchase->getQuantity($sumQty)
            //$purchase->getQuantity_m($sumQty_m)
            //Top-5 Books
            $purchase->book = Purchase::where('qty')->orderBy('qty','desc')->take(5)->get();
            //Top-5 Magazins
            $purchase->magazine = Purchase::where('qty')->orderBy('qty','desc')->take(5)->get();

        }

        return response()->json($purchases,200);
    }
}
