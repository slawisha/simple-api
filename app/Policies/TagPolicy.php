<?php

namespace App\Policies;

use App\Tag;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy
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

    /**
     * Can user update  tag
     * 
     * @param  User   $user 
     * @param  Tag    $tag  
     * @return bool       
     */
    public function update(User $user, Tag $tag)
    {
        return $user->getAuthenticatedUser()->id == $tag->user_id;

    }

    /**
     * Can user delete  tag
     * 
     * @param  User   $user 
     * @param  Tag    $tag  
     * @return bool       
     */
    public function destroy(User $user, Tag $tag)
    {
        return $user->getAuthenticatedUser()->id == $tag->user_id;
    }
}
