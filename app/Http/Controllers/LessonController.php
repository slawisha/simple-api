<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Lesson;
use App\Transformers\LessonTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class LessonController extends ApiController
{
    /**
     * List all resources
     * 
     * @return @Response
     */
    public function index(Request $request)
    {
        $limit = $this->limiter->setItemsPerPage($request->get('limit'));

    	$paginator = Lesson::with('user', 'tags')->paginate($limit);

        $lessons = $paginator->getCollection();

    	return $this->respondWithCollection($paginator, $lessons, new LessonTransformer);
    }

    /**
     * Show a single resource
     *     
     * @param  int $id 
     * @return @Response   
     */
    public function show($id)
    {
    	try {
    	   $lesson = Lesson::with('tags')->findOrFail($id);
    		
    	   return $this->respondWithItem($lesson, new LessonTransformer);
    	
    	} catch (ModelNotFoundException $e) {
    	
            return $this->errorNotFound();
            		
    	}

    }
}
