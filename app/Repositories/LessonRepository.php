<?php

namespace App\Repositories;

use App\Lesson;
use App\User;
use Gate;
use Illuminate\Http\Request;

class LessonRepository
{
	/**
	 * Find lesson biy its id
	 * 
	 * @param  int $id 
	 * @return App\Lesson   
	 */
	protected function findById($id)
	{
		return app('App\Lesson')->findOrFail($id);
	}
	/**
	 * Get all lesson paginated
	 * 
	 * @param  int $limit number of lessons per page
	 * @return Illuminate\Pagination\Paginator       
	 */
	public function allPaginated($limit)
	{
		return Lesson::with('user', 'tags')->paginate($limit);
	}
	/**
	 * Find specific lesson
	 * 
	 * @param  int $id 
	 * @return App\Lesson     
	 */
	public function findLessonWithTags($id)
	{
		return app('App\Lesson')->with('tags')->findOrFail($id);
	}
	/**
	 * Create a lesson
	 * 			
	 * @param  Request $request 
	 * @param  User    $user  
	 * @return void      
	 */
	public function create(Request $request, User $user)
	{
		$title = $request->input('title');
        $body = $request->input('body');
        $tagId = $request->input('tag_id') ? : 1;

        $lesson = app('App\Lesson')->create([
                'title' => $title,
                'body' => $body,
                'user_id' => $user->id
            ]);


        $lesson->tags()->attach($tagId);
	}

	/**
	 * Update a lesson
	 * 
	 * @param  Request $request 
	 * @param  int  $id
	 * @return bool           
	 */
	public function update(Request $request, $id)
	{
		$title = $request->input('title');

        $body = $request->input('body');
        
        $lesson = Lesson::with('tags')->findOrFail($id);

        if(Gate::denies('update', $lesson)) {
            return false; 
        }

        $lesson->title = $title;

        $lesson->body = $body;

       	$lesson->save();

       	return true;

	}

	/**
	 * Delete a lesson
	 * 
	 * @param  Request $request 
	 * @param  int  $id
	 * @return bool           
	 */
	public function delete($id)
	{
		  $lesson = $this->findById($id);

            if(Gate::denies('destroy', $lesson)) {
                return false; 
            }

            $lesson->delete();

            return true;
	}

}