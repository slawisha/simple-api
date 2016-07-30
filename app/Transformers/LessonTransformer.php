<?php

namespace App\Transformers;

use App\Lesson;
use League\Fractal\TransformerAbstract;

class LessonTransformer extends TransformerAbstract 
{

	public function transform(Lesson $lesson)
	{
		return [
			'id' => (int) $lesson->id,
			'title' => $lesson->title,
			'body' => $lesson->body,
			'author' => $lesson->user->name,
			'last_updated' => (string)$lesson->updated_at,
			'tags' => $lesson->tags()->lists('name')->toArray()
		];
	}
}