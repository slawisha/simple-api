<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Lesson;
use App\Repositories\LessonRepository;
use App\Traits\PaginatorLimiterTrait;
use App\Transformers\LessonTransformer;
use App\Utilities\PaginatorLimiter;
use Gate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use League\Fractal\Manager;

class LessonController extends ApiController
{
    private $lessonRepo;

    use PaginatorLimiterTrait;

    public function __construct(Manager $fractal, LessonRepository $lessonRepo)
    {
        $this->middleware('jwt.auth', ['only' => [
                'store', 'update', 'destroy'
            ]]);

        parent::__construct($fractal);

        $this->lessonRepo = $lessonRepo;
    }
    /**
     * List all resources
     * 
     * @return @Response
     */
    public function index(Request $request)
    {
        $limit = $this->setItemsPerPage($request->get('limit'));

    	$paginator = $this->lessonRepo->allPaginated($limit);

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

           $lesson = $this->lessonRepo->findLessonWithTags($id);
    		
    	   return $this->respondWithItem($lesson, new LessonTransformer);
    	
    	} catch (ModelNotFoundException $e) {
    	
            return $this->errorNotFound();
            		
    	}

    }

    /**
     * Store a lesson
     * 
     * @param  Request $request 
     * @return @Response           
     */
    public function store(Request $request)
    {
        $this->validateInput($request);
        
        $user = app('App\User')->getAuthenticatedUser();

        $this->lessonRepo->create($request, $user);

        return $this->respondWithSuccess();
    }

    /**
     * Update lesson
     * 
     * @param  Request $request 
     * @param  int  $id      
     * @return @Response           
     */
    public function update(Request $request, $id)
    {
        check_users_token();

        $this->validateInput($request);

        try {

            if(!$this->lessonRepo->update($request, $id)) return $this->errorUnauthorized();

            return $this->respondWithSuccess('Item updated');
        
        } catch (ModelNotFoundException $e) {
        
            return $this->errorNotFound();
                    
        } 
    }

    /**
     * Destroy lesson
     * 
     * @param  int $id 
     * @return @Response
     */
    public function destroy($id)
    {
        check_users_token();
        
        try {
            
            if(!$this->lessonRepo->delete($id)) return $this->errorUnauthorized();


        } catch (ModelNotFoundException $e) {

            return $this->errorNotFound();

        } 

        return $this->respondWithSuccess('Item deleted');
    }

    /**
     * Validate request
     *     
     * @param  Request $request
     * @return mixed
     */
    private function validateInput(Request $request)
    {
        $this->validate($request, [
                'title' => 'required',
                'body' => 'required'
            ]);
    }
}