<?php

namespace App\Http\Controllers;

use Auth;
USE Session;
use App\Magazine;
use App\Book;
use App\Order;
use App\Purchase;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Purchase::class, 'purchase');
    }
    // public function setUser($purchase_id, $user_id)
    // {
    //     $purchase = Purchase::findOrFail($purchase_id);
    //     $user = User::findOrFail($user_id);
    //     $user->purchases()->save($purchase);

    //     return response()->json($purchase->load('user'), 200);
    // }// to model

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchases = \App\Http\Resources\PurchasesCollection::make(Purchase::with('book', 'magazine', 'order')->where('status_paid', '!=', '1')->get());

        return response()->json($purchases,200));//homes.pay
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PurchaseRequest $request)
    {

        $purchase = Purchase::create($request->all()->validated());
        //$purchase->create($request->all());
        //$purchase->getNewPriceAttributes($request->get('newPrice'));//?
        $purchase->toggleStatusBuy();
        
        return response()->json($purchase,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        return response()->json($purchase->load('order'),200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(PurchaseRequest $request, Purchase $purchase)
    {
        $purchase->update($request->all());
        //$purchase->getNewPriceAttributes($request->get('newPrice'));//?

        return response()->json($purchase,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return response()->json(null,200);
    }

    public function destroyAll(Request $request)
    {
        $purchases = \App\Http\Resources\PurchaseCollection::make(Purchase::all()->where('status_paid', '==', '0')->where('status_sub_price', '==', '0')->get());
        foreach ($purchases as $purchase) {

            $purchase->delete();
        }
        return response()->json(null,200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function indexCart()//
    {        
        $purchases = \App\Http\Resources\PurchaseCollection::make(Purchase:::with('book', 'magazine', 'order')->where('status_paid', '==', '0')->get());

        return response()->json($purchase,200);//homes.pay
        

    }


  //   /**
  //  * Отправка пользователю напоминания по e-mail.
  //  *
  //  * @param  Request  $request
  //  * @param  int  $id
  //  * @return Response
  //  */
  // public function sendEmailReminder(Request $request, $id)
  // {
  //   $user = User::findOrFail($id);

  //   Mail::send('emails.reminder', ['user' => $user], function ($m) use ($user) {
  //     $m->from('innadanylevska@gmail.com', 'Shop');

  //     $m->to($user->email, $user->name)->subject('Your Order!');
  //   });
  // }

    

    //MODELpublic function toggleStatus()
    
 //cart
    //use Cache;
    //pub item:
    //relat item belowTo cart $item_id
       /**
     * Store an item in the cache.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @param  int     $seconds
     * @return array|bool
     */
    public function addToCart($id, $count, $price){
            // $this->item = put($key, $value, $seconds);//?cookie
            // $this->item = start();//?session
            // return $this;
        //or purchaseable???


            $this->purchaseable()s = $_SESSION['products'];//?session
            if(array_key_exists(($id), $this->purchaseable()s)){
                $this->purchaseable()s['id']['count'] += $count;

            }else{
                $this->purchaseable()s = ['id'][

                    'id' => $id,
                    'count' => $count,

                ];
            }

            $_SESSION = $this->purchaseable()s;

    }

    //
    public function remove(){
        $this->purchaseable()s = $_SESSION['products'];
        $this->purchaseable()s = arraay_diff_key($this->purchaseable()s, [$id => []]);
        $_SESSION['products'] = $this->purchaseable()s;
    }

    public function total(){
        $this->purchaseable()s = $_SESSION['products'];
        foreach($this->purchaseable()s as $this->purchaseable()){
            return $total += $purchaseable()['count'] * $price['price'];
        }
    }

    public function buy($order)//, $summa trait
    {
        $purchases = \App\Http\Resources\PurchaseCollection::make(Purchase::with('book', 'magazine', 'order')->where('status_bought', '==', '1')->where('status_paid', '==', '0')->get());
        $order = new Order(); 
        $order = \App\Http\Resources\OrderCollection::make(Order::add($request->all()));
        $order->setCountAttributes($request->get('count')); 
        $order->setTotalAttributes($request->get('total'));   
        $order->setCode($request->get('code'));
        foreach ($purchases as $purchase){
            $purchase->order_id = $order->id;
            $purchase->toggleStatus();  
            $purchase->sendEmailReminder();//action lisiner observe 
        }     
        $order->save(); 
        return response()->json($purchases,200);       

        //return redirect()->route('cart')->with('status_paid','Check your email!');//to payment service
    }

    public function toggleBuyAll()//toggleBeforeToggleAll()//not work
    {
        $purchases = \App\Http\Resources\PurchaseCollection::make(Purchase::where('status_bought', '==', '0')->where('status_paid', '==', '0')->get());

        foreach ($purchases as $purchase) 
        {

            $purchase->toggleStatusBuy();
        }
            

        return response()->json($purchase,200);
    }

    public function toggleBuy($id)//toggleBeforeToggle($id)
    {

        $purchase = Purchase::find($id);//where it got// order in toggle
        $purchase->toggleStatusBuy();

        
        return response()->json($purchases,200);
    }

    public function toggleIsPausedSubPrice($id)//toggleBeforeToggle($id)
    {

        $purchase = Purchase::find($id);//where it got// order in toggle
        $purchase->getIsStatusSubPriceAttribute();

        
        return response()->json($purchases,200);
    } 

    //any_disconts

    public function toggleIsPausedDiscontId($id)//toggleBeforeToggle($id)
    {

        $purchase = Purchase::find($id);//where it got// order in toggle
        $purchase->getStatusVisibleDiscontIdAttribute();

        
        return response()->json($purchases,200);
    } 
}
