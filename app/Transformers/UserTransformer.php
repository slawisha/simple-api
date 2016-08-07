<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
	/**
	 * Transform user
	 * 
	 * @param  User   $user 
	 * @return array       
	 */
	public function transform(User $user)
	{

		$lessons = $this->transformNestedLessons($user);

		return [
			'name' =>  $user->name,
			'email' => $user->email,
			'lessons' => $lessons
		];

	}

	/**
	 * Transform users lessons
	 * 
	 * @param  User   $user 
	 * @return array       
	 */
	private function transformNestedLessons(User $user)
	{

		$lessons = []; 

		foreach ($user->lessons()->get() as $lesson) {
			
			$lessons [] = [
				'title' => $lesson->title,
				'url' => url('/api/v1/lesson/',$lesson->id)
			];

		}

		return $lessons;

	}

}