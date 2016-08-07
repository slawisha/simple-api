<?php

namespace App\Transformers;

use App\Lesson;
use League\Fractal\TransformerAbstract;

class LessonTransformer extends TransformerAbstract 
{
	/**
	 * Transform a lesson
	 * 
	 * @param  Lesson $lesson 
	 * @return array         
	 */
	public function transform(Lesson $lesson)
	{
		return [
			'id' => (int) $lesson->id,
			'title' => $lesson->title,
			'body' => $lesson->body,
			'author' => $lesson->user->name,
			'url' => url('/api/v1/lesson/' . $lesson->id),
			'last_updated' => (string)$lesson->updated_at,
			'tags' => $lesson->tags()->lists('name')->toArray()
		];
	}
}