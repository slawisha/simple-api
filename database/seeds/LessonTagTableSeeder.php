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
            $tagIds = App\Tag::where('user_id', $lesson->user_id)->lists('id')->toArray();
        	$randTagIdKeys = (array) array_rand( $tagIds, rand(1,count($tagIds)));
        	foreach ( $randTagIdKeys as $key) {
	            DB::table('lesson_tag')->insert([
	                'lesson_id' => $lesson->id,     
	                'tag_id' => $tagIds[$key]
	            ]);
        	}
        }
    }
}
