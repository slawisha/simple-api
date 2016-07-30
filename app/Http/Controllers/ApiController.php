<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Utilities\PaginatorLimiter;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class ApiController extends Controller
{
	protected  $statusCode = 200;

	const CODE_WRONG_ARGS = 'You passed wrong arguments';
	const CODE_NOT_FOUND = 'Could not found resource';
	const CODE_UNAUTHORIZED = 'You are not authorized to view ths page';
	const CODE_FORBIDEN = 'The content is forbiden';
	const CODE_INTERNAL_ERROR = 'Something is wrong with our server, please try again later';

    public function __construct(Manager $fractal, PaginatorLimiter $limiter)
    {
    	$this->fractal = $fractal;
    	
    	$this->limiter = $limiter;
    }

    public function getStatusCode()
    {
    	return $this->statusCode;
    }

    public function setStatusCode($statusCode)
    {
    	$this->statusCode = $statusCode;
    	return $this;
    }

    protected function respondWithItem($item, $callback)
    {
    	$resource = new Item($item, $callback);

    	$rootscope = $this->fractal->createData($resource);

    	return $this->respondWithArray($rootscope->toArray());
    }

    protected function respondWithCollection($paginator, $collection, $callback)
    {
    	$resource = new Collection($collection, $callback);

    	$resource-> setPaginator( new IlluminatePaginatorAdapter( $paginator));

    	$rootscope = $this->fractal->createData($resource);

    	return $this->respondWithArray($rootscope->toArray());
    }

    /**
     * Convert array of data to json	
     * @param  array  $array   
     * @param  array  $headers 
     * @return @Response          
     */
    protected function respondWithArray(array $array, array $headers = [])
    {
    	return response()->json($array, $this->statusCode, $headers);
    }
    /**Create json error output 
     * 
     * @param  string $message   
     * @param  string $errorCode 
     * @return @Response           
     */
    protected function respondWithError($message, $errorCode)
    {
    	return $this->respondWithArray([
    			'error' => $errorCode,
    			'http_code' => $this->statusCode,
    			'message' => $message
    		]);
    }

    /**
     * Create a response with a 403 HTTP header and a given message
     * 
     * @param  string $message 
     * @return @Response         
     */
    public function errorForbiden($message = 'Forbiden')
    {
    	return $this->setStatusCode(403)->respondWithError($message, self::CODE_FORBIDEN);
    }

    /**
     * Create a response with a 500 HTTP header and a given message
     * 
     * @param  string $message 
     * @return @Response          
     */
    public function errorInternalError($message = 'Internal error')
    {
		return $this->setStatusCode(500)->respondWithError($message, self::CODE_INTERNAL_ERROR);
    }

    /**
     * Create a response with a 404 HTTP header and a given message
     * 
     * @param  string $message 
     * @return @Response          
     */
    public function errorNotFound($message = 'Resource not found')
	{
		return $this->setStatusCode(404)->respondWithError($message, SELF::CODE_NOT_FOUND);
	}

	/**
     * Create a response with a 401 HTTP header and a given message
     * 
     * @param  string $message 
     * @return @Response          
     */
    public function errorUnauthorized($message = 'Unauthorized')
	{
		return $this->setStatusCode(401)->respondWithError($message, SELF::CODE_UNAUTHORIZED);
	}

	/**
     * Create a response with a 400 HTTP header and a given message
     * 
     * @param  string $message 
     * @return @Response          
     */
    public function errorWrongArgs($message = 'Wrong arguments')
	{
		return $this->setStatusCode(400)->respondWithError($message, SELF::CODE_WRONG_ARGS);
	}








}
