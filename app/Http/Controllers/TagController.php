<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Repositories\TagRepository;
use App\Traits\PaginatorLimiterTrait;
use App\Transformers\TagTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use League\Fractal\Manager;


class TagController extends ApiController
{
    private $tagRepo;

    use PaginatorLimiterTrait;

    public function __construct(Manager $fractal, TagRepository $tagRepo)
    {
        $this->middleware('jwt.auth', ['only' => [
                'store', 'update', 'destroy'
            ]]);

        parent::__construct($fractal);

        $this->tagRepo = $tagRepo;
    }

    /**
     * list all tags
     * 
     * @return @Response
     */
    public function index(Request $request)
    {
    	$limit = $this->setItemsPerPage($request->get('limit'));

    	$paginator = $this->tagRepo->allPaginated($limit);

    	$tags = $paginator->getCollection();

    	return $this->respondWithCollection($paginator, $tags, new TagTransformer);
    }

    public function show($id)
    {
    	try{

    		$tag = $this->findTagWithLessons($id);
    		
    		return $this->respondWithItem($tag, new TagTransformer);
    		
    	} catch (ModelNotFoundException $e) {

    		return $this->errorNotFound();

    	} 
    }

    /**
     * Store a resource
     * 
     * @param  Request $request 
     * @return @Response           
     */
    public function store(Request $request)
    {
        $this->validateInput($request);

        $user = app('App\User')->getAuthenticatedUser();

        $this->tagRepo->create($request, $user);

        return $this->respondWithSuccess('Tag created');

    }

    /**
     * Update a resource
     * 
     * @param  Request $request 
     * @return @Response           
     */
    public function update(Request $request, $id)
    {
        $this->validateInput($request);

        try {

            if(! $this->tagRepo->update($request, $id)) return $this->errorUnauthorized(); 

            return $this->respondWithSuccess('Tag updated');

            
        } catch (ModelNotFoundException $e) {

            return $this->errorNotFound();
            
        }
    }

    /**
     * Destroy resource 
     * 
     * @param  int $id 
     * @return @Response     
     */
    public function destroy($id)
    {
        try{

            if(! $this->tagRepo->delete($id)) return $this->errorUnauthorized();

        } catch (ModelNotFoundException $e) {

            return $this->errorNotFound();

        }

        return $this->respondWithSuccess('Tag deleted');
    }

    private function validateInput(Request $request)
    {
        $this->validate($request, [
                'name' => 'required'
            ]);
    }
}
