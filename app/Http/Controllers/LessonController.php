<?php

namespace App\Http\Controllers;

use App\Exceptions\UserNotLessonOwnerException;
use App\Http\Requests;
use App\Lesson;
use App\Traits\PaginatorLimiterTrait;
use App\Transformers\LessonTransformer;
use App\Utilities\PaginatorLimiter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use League\Fractal\Manager;

class LessonController extends ApiController
{
    use PaginatorLimiterTrait;

    public function __construct(Manager $fractal)
    {
        $this->middleware('jwt.auth', ['only' => [
                'store', 'update', 'delete'
            ]]);

        parent::__construct($fractal);
    }
    /**
     * List all resources
     * 
     * @return @Response
     */
    public function index(Request $request)
    {

        $limit = $this->setItemsPerPage($request->get('limit'));

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

    public function store(Request $request)
    {
        $this->validateInput($request);

        $title = $request->input('title');
        $body = $request->input('body');
        $user = app('App\User')->getAuthenticatedUser();
        $tagId = $request->input('tag_id') ? : 1;

        $lesson = app('App\Lesson')->create([
                'title' => $title,
                'body' => $body,
                'user_id' => $user->id
            ]);


        $lesson->tags()->attach($tagId);

        return $this->respondWithSuccess();
    }

    public function update(Request $request, $id)
    {
        $this->validateInput($request);

        try {
           $lesson = Lesson::with('tags')->findOrFail($id);
            
            $title = $request->input('title');
            $body = $request->input('body');
            $user = app('App\User')->getAuthenticatedUser();
            //$tagId = $request->input('tag_id') ? : 1;

            if($lesson->user->id != $user->id)
                 throw new UserNotLessonOwnerException("You don't own this Lesson");
            

           $lesson->title = $title;
           $lesson->body = $body;

           $lesson->save();

           return $this->respondWithSuccess('Item updated');
        
        } catch (ModelNotFoundException $e) {
        
            return $this->errorNotFound();
                    
        } catch (UserNotLessonOwnerException $e) {
            return $this->errorUnauthorized($e->getError());
        }
    }

    public function destroy($id)
    {
        try {
            $lesson = Lesson::findOrFail($id);

            $user = app('App\User')->getAuthenticatedUser();

            if($lesson->user->id != $user->id)
                 throw new UserNotLessonOwnerException("You don't own this Lesson");

            $lesson->delete();


        } catch (ModelNotFoundException $e) {

            return $this->errorNotFound();

        } catch (UserNotLessonOwnerException $e) {

            return $this->errorUnauthorized($e->getError());

        }

        return $this->respondWithSuccess('Item deleted');
    }

    private function validateInput(Request $request)
    {
        $this->validate($request, [
                'title' => 'required',
                'body' => 'required'
            ]);
    }
}