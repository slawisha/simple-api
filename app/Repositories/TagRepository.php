<?php

namespace App\Repositories;

use App\User;
use Gate;
use Illuminate\Http\Request;

class TagRepository
{
	/**
	 * Find tag by its id
	 * 
	 * @param  int $id
	 * @return  App\Tag
	 */
	public function findTagById($id)
	{
		return app('App\Tag')->findOrFail($id);
	}

	/**
	 * Find tag with lessons
	 * 
	 * @param  int $value
	 * @return App\Tag
	 */
	public function findTagWithLessons($id)
	{
		return app('App\Tag')->with('lessons')->findOrFail($id);
	}

	/**
	 * Find all tags paginated
	 * 
	 * @param  int $limit 
	 * @return Illuminate\Pagination\Paginator   
	 */
	public function allPaginated($limit)
	{
		return app('App\Tag')->with('lessons')->paginate($limit);
	}

	/**
	 * Create tag
	 * 
	 * @param  Request $request 
	 * @return void   
	 */
	public function create(Request $request, User $user)
	{
		$name = $request->get('name');

		app('App\Tag')->create([
                'name' => $name,
                'user_id' => $user->id 
        ]);
	}

	/**
	 * Update tag
	 * 
	 * @param  Request $request 
	 * @return bool   
	 */
	public function update(Request $request, $id)
	{
		$tag = $this->findTagById($id);

        if(Gate::denies('update', $tag)) {
            return false; 
        }
        
        $name = $request->input('name');

        $tag->name = $name;

        $tag->save();

        return true;	
	}

	/**
	 * Delete tag
	 * 
	 * @param  Request $request 
	 * @return bool   
	 */
	public function delete($id)
	{
		$tag = $this->findTagById($id);

		if(Gate::denies('destroy', $tag)) {
            return false; 
        }

		$tag->delete();

		return true;
	}
}