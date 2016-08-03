<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Transformers\TagTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
class TagController extends ApiController
{
    /**
     * list all tags
     * 
     * @return @Response
     */
    public function index(Request $request)
    {
    	$limit = $this->limiter->setItemsPerPage($request->get('limit'));

    	$paginator = app('App\Tag')->with('lessons')->paginate($limit);

    	$tags = $paginator->getCollection();

    	return $this->respondWithCollection($paginator, $tags, new TagTransformer);
    }

    public function show($id)
    {
    	try{

    		$tag = app('App\Tag')->with('lessons')->findOrFail($id);
    		
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
        $this->validate($request, [
            'name' => 'required'
        ]);

        $name = $request->get('name');

        app('App\Tag')->create(['name' => $name]);

        return $this->respondWithSuccess();

    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
                'name' => 'required'
            ]);

        $name = $request->input('name');

        try {

            $tag = app('App\Tag')->findOrFail($id);

            $tag->name = $name;

            $tag->save();

            return $this->respondWithSuccess('Item updated');
            
        } catch (ModelNotFoundException $e) {

            return $this->errorNotFound();
            
        }
    }
}
