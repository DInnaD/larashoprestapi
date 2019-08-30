<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
	protected $fillable['user-id', 'order_id', 'book_id', 'magazine_id', 'qty', 'status_bought', 'status_paid', 'status_discont_id','created_by', ];

	/**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    	'code' => 'integar',
    	'count' => 'integar',
    	'total' => 'float',
    	'statusSubPrice' => 'boolean',
    	'statusVisibleDiscontId' => 'boolean',
    	'statusVisibleDiscontGlobal' => 'boolean',
    ];//'isPausedPublished' => 'boolean',
    protected $attributes = ['code', 'total', 'count', 'statusSubPrice', 'statusVisibleDiscontId', 'statusVisibleDiscontGlobal',
       // 'status_draft' => 10,  'isPausedPublished',
    ];
    protected $touches = ['order', 'user', 'book', 'magazine',
       // 'status_draft' => 10
    ];

    protected $appends = ['code'];    
	protected $appends = ['count'];
	protected $appends = ['total'];
	protected $appends = ['statusSubPrice'];
    protected $appends = ['statusVisibleDiscontId'];
    protected $appends = ['statusVisibleDiscontGlobal'];
   //protected $appends = ['isPausedPublished'];
    //53 bookmodel 
    // public function getStatusDraft()//?????BEFORE PAYABLE ACTION?????//
    // {
    //     return $this->purchases()->where('status_draft', 1)->get();
    // }
    // public function getStatusDraft()//??????????// for payable before
    // {
    //     return $this->book->where('status_draft', 1)->get();
    // }

	public function author()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
    	return $this->belongsTo(Order::class, 'order_id');
    }

	public function purchaseable()
    {
        return $this->morphTo();   
    }

     

	public function setCountAttributes()
    {
    	foreach($purchases as $purchase){
    	$count += $purchase->qty; 
    	}
    	$this->attributes['count'] = $count;	
    }

    public function getCountAttributes()
    {
    	foreach($purchases as $purchase){
    	$count += $purchase->qty; 
    	return $count;	
    }

    public function getCount()
    {
        return $count = sum($this->qty); 
    }
 
     public function setTotalAttributes()
    {
    	$total = sum($purchase->qty * $purchase->purchaseable()->newPrice);
    	$this->attributes['total'] = $total;	
    }

    public function getTotalAttributes()
    {
    	$total = sum($purchase->qty * $purchase->purchaseable()->newPrice);
    	return $total;	
    }

    public function getTotal()
    {
        return $total = sum($this->qty * $this->purchaseable()->newPrice); 
    }

    public function setCodeAttributes()
    {
    	$code = $purchase->purchaseable()->code;
    	$this->attributes['code'] = $code;//random	
    }

    public function getCodeAttributes()//? to 1
    {
    	$code = $purchase->purchaseable()->code;
    	return $code;	
    }

    public function getCode()//?
    {
    	return $code = $this->purchaseable()->code;
    }

    public function Buy()
    {
    	
    	$this->status_bought = 1;
    	$this->save();
    	
    }

    public function disBuy()
    {
    	$this->status_bought = 0;
    	$this->save();
    }

    public function toggleStatusBuy()
    {
    	if($this->status_bought == 0)
    	{
    		return $this->Buy();
    	}

    	return $this->disBuy();
    }   

     
    //for admin controller
    public function pay()
    {
    	
    	$this->status_paid = 1;
    	$this->save();
    	
    }

    public function disPay()
    {
    	$this->status_paid = 0;
    	$this->save();
    }

    public function toggleStatus()
    {
    	if($this->status_paid == 0)
    	{
    		return $this->pay();
    	}

    	return $this->disPay();
    }

    public function sub()
    {
        
        $this->purchaseable()->status_sub_price = 1;
        $this->save();
        
    }

    public function disSub()
    {
        $this->purchaseable()->status_sub_price = 0;
        $this->save();
    }

    public function getStatusSubPriceAttribute()//status_sub_price magazine->status_draft
     {
          if($this->purchaseable()->status_sub_price == 0)
        {
            return $this->sub();
        }

        return $this->disSub();
     }

         public function makeVisibleDiscontId()
    {
        $this->purchaseable()->status_discont_id = 0;
        $this->save();
    }

    public function makeUnVisibleDiscontId()
    {
        $this->purchaseable()->status_discont_id = 1;
        $this->save();
    }

    public function getStatusVisibleDiscontIdAttribute()
    {
        if($this->purchaseable()->status_discont_id == 1)
        {
            return $this->makeVisibleDiscontId();
        }

        return $this->makeUnVisibleDiscontId();
    }

    public function makeVisibleGlobal()
    {
        $this->purchaseable()->status_discont_global = 0;
        $this->save();
    }

    public function makeUnVisibleGlobal()
    {
        $this->purchaseable()->status_discont_global = 1;
        $this->save();
    }
    //inside
    public function getStatusVisibleGlobalAttribute()
    {
        if($this->purchaseable()->status_discont_global == 1)
        {
            return $this->makeVisibleGlobal();
        }

        return $this->makeUnVisibleGlobal();
    }
}
