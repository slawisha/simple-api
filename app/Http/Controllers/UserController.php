<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Traits\PaginatorLimiterTrait;
use App\Transformers\UserTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    use PaginatorLimiterTrait;

    public function index(Request $request)
    {
    	$limit = $this->setItemsPerPage($request->get('limit')); 

    	$paginator = app('App\User')->with('lessons')->paginate($limit);

    	$users = $paginator->getCollection();

    	return $this->respondWithCollection($paginator, $users, new UserTransformer);
    }

    public function show($id)
    {
    	try {
    		
    		$user = app('App\User')->with('lessons')->findOrFail($id);

    		return $this->respondWithItem($user, new UserTransformer);

    	} catch (ModelNotFoundException $e) {
    		
			return $this->errorNotFound();

    	}
    }
}
