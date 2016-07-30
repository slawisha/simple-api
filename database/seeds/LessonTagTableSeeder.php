<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LessonTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (App\Lesson::all() as $lesson) {
        	$tags = (array) array_rand(App\Tag::lists('id')->toArray(),rand(1,3));
        	foreach ( $tags as $tag) {
	            DB::table('lesson_tag')->insert([
	                'lesson_id' => $lesson->id,     
	                'tag_id' => $tag 
	            ]);
        	}
        }
    }
}
