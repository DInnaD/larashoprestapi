<?php

namespace App;

use \Storage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes, Selectable; //Relations\BelongsTo\User,Relations\MorphMany\Purchases;
//, Owned//, Searchable; 

    protected $fillable = ['user_id', 'item_id', 'name', 'author_name', 'lenght', 'publisher', 'year', 'format', 'genre', 'dimensions', 'price', 'old_price', 'img','code', 'discont_global', 'status_discont_global', 'discont_id', 'status_discont_id', 'created_by'];//'new_price - set value from virtual field here???
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'newPrice' => 'float',// realField price
        'currentDiscont' => 'integer',
        //'date'
    ];
    protected $attributes = ['newPrice',
    'currentDiscont',
       //'isPausedPublished' => true, 'isSoftDeleted', 
    ];
    protected $touches = ['purchase','user',
    ];
    protected $appends = ['newPrice', 'currentDiscont', 
    ];

    //$date = [];
    const IS_DRAFT = 0;
    const IS_PUBLIC = 1;//published
    const IS_HARDCOVER = 0;//select 
    const IS_KINDLE = 1;

    public function author()//isAdmin
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function genres()
    {
        return $this->belongsToMany('App\Models\Genre');
    }

    public function purchases()//+boot
    {
        return $this->morphMany('App\Purchase', 'purchaseable');   
    } 

    public function getValueIsHardCover()
    {
        ////checkBox 
        return $this->is_hard_cover = Input::make($fields['is_hard_cover']);
        // $this->fill($fields); //name,email
        // $this->is_hard_cover = Input::make($fields['is_hard_cover']);
        // $this->save();
    }
//OR
    //selectable AudioBook Audio CD
    public function setHardCover()//chernovik
    {
        $this->is_hard_cover = Book::IS_HARDCOVER;
        $this->save();
    }

    public function setKindle()
    {
        $this->is_hard_cover = Book::IS_KINDLE;
        $this->save();
    }

    public function toggleStatusBookFormat()
    {
        if($this->is_hard_cover == 0)
        {
            return $this->setNoHard();
        }

        return $this->setHard();
    }


    public function remove()
    {
        //$this->toggleStatusSoftDeletesUnPublished();
        $this->removeImage();
        $this->delete();
    }

    // public function restore()
    // {
    //     return $this->restore();   
    // }

    public function removeImage()
    {
        if($this->img != null)//rewrite
        {
            Storage::delete('uploads/' . $this->img);
        }
    }

    public function uploadImage($img)
    {
        if($img == null) { return; }

        $this->removeImage();
        $filename = str_random(10) . '.' . $img->extension();
        $img->storeAs('uploads', $filename);
        $this->img = $filename;
        $this->save();
    }

    public function getImage()
    {
        if($this->img == null)
        {
            return '/img/no-image.png';
        }

        return '/uploads/' . $this->img;

    }
     
    



     public function getCurrentDiscontAttributes()
        {
            return $currentDiscont = $this->shouldShowCurrentDiscontBasic();
        }



    public function getNewPriceAttributes()
    {
        return $newPrice = $this->shouldShowNewPriceBasic();
    }



    public function makeVisibleDiscontGlobal()
    {
        $this->status_discont_global = 1;
        $this->save();
    }

    public function makeUnVisibleDiscontGlobal()
    {
        $this->status_discont_global = 0;
        $this->save();
    }

    public function toggleStatusVisibleGl()
    {
        if($this->status_discont_global == 0)//!= null default i nullable is the same?
        {
            return $this->makeVisibleDiscontGlobal();
        }

        return $this->makeUnVisibleDiscontGlobal();
    }

    public function makeVisibleDiscontId()//as single func
    {
        $this->status_discont_id = 1;
        $this->save();//polzovatelskiy package chrom #5 obyazatelnyh paketov laravel.https://laravel.ru/posts/148
    }

    public function makeUnVisibleDiscontId()//as single func
    {
        $this->status_discont_id = 0;
        $this->save();
    }



        // public function shouldShowNewPrice()
 //    {
        
 //     return $this->shouldShowNewPriceBasic();    
        
 //    }

    public function shouldShowNewPriceBasic()
    {
        if($this->shouldShowNewPriceDiscontIdBasic() | $this->shouldShowNewPriceDiscontGlobalBasic() | $this->getNewPrice()){
            return $this->newPrice = $newPrice;
      
        }
    }

    public function shouldShowNewPriceDiscontIdBasic()
    {
        if($this->shouldShowNewPriceDiscontId()){
            return $this->getNewPriceDiscontId();
       }
    }

    public function shouldShowNewPriceDiscontGlobalBasic()
    {
        if($this->shouldShowNewPriceDiscontGlobal()){
            return $this->getNewPriceDiscontGlobal();
        }
    }

    public function getNewPrice()
    {
        return $newPrice = $this->price; 
    }

    public function shouldShowNewPriceDiscontId()
    {
        return (($this->author->isPausedDiscontId == false && $this->status_discont_id == 1) && $this->discont_global < $this->discont_id) or ($this->author->isPausedDiscontGlobal == true or $this->status_discont_global == 0);
    }    

     public function shouldShowNewPriceDiscontGlobal()
    {
        return (($this->author->isPausedDiscontGlobal == false && $this->status_discont_global == 1) && $this->discont_global >= $this->discont_id) or ($this->author->isPausedDiscontId == true or $this->status_discont_id == 0);
    }

    public function getNewPriceDiscontId()
    {
        $newPrice = $this->price - ($this->price * $this->discont_id / 100);
        return $newPrice;   
    }

    public function getNewPriceDiscontGlobal()
    {
        $newPrice = $this->price - ($this->price * $this->discont_global / 100);
        return $newPrice;   
    }
    
    // public function shouldShowCurrentDiscont()
    // {
    //     return $this->shouldShowCurrentDiscontBasic();
    // }

    public function shouldShowCurrentDiscontBasic()
    {
        if($this->shouldShowCurrentDiscontIdBasic() or $this->shouldShowCurrentDiscontGlobalBasic()){
            return $this->currentDiscont;
        }
        return $this->getCurrentDiscont();
    }

    public function shouldShowCurrentDiscontIdBasic()
    {
        if($this->shouldShowNewPriceDiscontId()){
            return $this->getCurrentDiscontId();
        }
    }

    public function shouldShowCurrentDiscontGlobalBasic()
    {
        if($this->shouldShowNewPriceDiscontGlobal()){
            return $this->getCurrentDiscontGlobal();
       }
    } 

    public function getcurrentDiscont()
    {
        return $currentDiscont = 0; 
    }

    public function getCurrentDiscontId()
    {
        $currentDiscont = $this->discont_id;
        return $currentDiscont;   
    }

    public function getCurrentDiscontGlobal()
    {
        $currentDiscont = $this->discont_global;
        return $currentDiscont;   
    }
   
}



