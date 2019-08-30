<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    //index show
    /**
     * Determine if the given user can create posts.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return true;
    }

    public function view(User $user)
    {
        return true;
    }

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  \App\User  $user
     * @param  \App\Book  $book
     * @return bool
     */
   
    public function update(User $user, Book $book)
    {// if($user->admin == true)
        return $user->id == $book->user_id;
    }
//     public function update(User $user, Post $post)
// {
// if($user->type === 'admin'){???? if($user->admin == true)
// return true;
// }

// return $user->id === $post->user_id;
// }
}
