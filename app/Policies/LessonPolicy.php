<?php

namespace App\Policies;

use App\Lesson;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LessonPolicy
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
     * Determine if a given user can delete lesson
     * 
     * @param  User   $user   
     * @param  Lesson $lesson 
     * @return bool
     */
    public function destroy(User $user, Lesson $lesson)
    {
        return $user->getAuthenticatedUser()->id == $lesson->user->id;
    }

    /**
     * Determine if a given user can update lesson
     * 
     * @param  User   $user   
     * @param  Lesson $lesson 
     * @return bool
     */
    public function update(User $user, Lesson $lesson)
    {
        return $user->getAuthenticatedUser()->id == $lesson->user->id;
    }
}
