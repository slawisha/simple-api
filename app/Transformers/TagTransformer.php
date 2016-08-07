<?php

namespace App\Transformers;

use App\Tag;
use League\Fractal\TransformerAbstract;

class TagTransformer extends TransformerAbstract
{
	/**
	 * Transform tag
	 * 
	 * @param  App\Tag  $tag 
	 * @return array
	 */
	public function transform(Tag $tag)
	{

		$lessons = $this->transformNestedLessons($tag);

		return [
			'id' => (int) $tag->id,
			'name' => $tag->name,
			'lessons' => $lessons
		];

	}

	/**
	 * Transform Nested Lessons for a tag
	 * 
	 * @param  Tag    $tag 
	 * @return array
	 */
	private function transformNestedLessons(Tag $tag)
	{

		$lessons = [];

		foreach ($tag->lessons()->get() as $lesson) {
			$lessons[] = [
				'name' => $lesson->title,
				'url' => url('/api/v1/lesson/',$lesson->id)
			];
		}

		return $lessons;

	}
}