<?php

namespace App;

use Hash;
use \Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

IS_ACTIVE = 1;
IS_BANNED = 0;
IS_ADMIN = 1;
//IS_USER = 0;

class User extends Authenticatable
{
    use SoftDeletes, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $touches = ['book', 'magazine', 'purchase', 'order',
    ];

    public function magazins()
    {
        return $this->hasMany(Magazin::class);
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function edit($fields)
    {
        $this->fill($fields); //name,email
        $this->password = Hash::make($fields['password']);
        $this->save();
    }

    public function shouldShowIsEmpty($varIsEmpty)//?????
    {
        return $this->is_empty($varIsEmpty);
    }

    /**
     * Generate random unique token.
     *
     * @return string $token
     */
    public function generateToken()
    {
        $this->api_token = str_random(60);
        while (User::where('api_token', $this->api_token)->first()) {
            $this->api_token = str_random(60);
        }
        $this->save();
        return $this->api_token;
    }

    public function generatePassword($password)
    {
        if($password != null)//is_empty($password)????
        {
            $this->password = Hash::make($password);
            $this->save();
        }
    }

    public function remove()
    {
        $this->removeAvatar();
        $this->delete();
    }
  
//add migration image
    public function uploadAvatar($image)
    {
        if($image == null) { return; }

        $this->removeAvatar();

        $filename = str_random(10) . '.' . $image->extension();
        $image->storeAs('uploads', $filename);
        $this->avatar = $filename;
        $this->save();
    }

    public function removeAvatar()
    {
        if($this->avatar != null)
        {
            Storage::delete('uploads/' . $this->avatar);
        }
    }

    public function getImage()
    {
        if($this->avatar == null)
        {
            return '/img/no-image.png';
        }

        return '/uploads/' . $this->avatar;
    }

    public function makeAdmin()
    {
        $this->is_admin = User::IS_ADMIN;
        $this->save();
    }

  

   
    public function getSwitcherAdminAttribute()
    {
        $switchAdmin = false;
        if (!$this->is_admin){
            $switchAdmin = true;
        }
    }

    

}

