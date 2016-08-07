<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class AuthenticateController extends ApiController
{
    /**
     * Authenicate user using jwtoken
     * 
     * @param  Request $request 
     * @return @Response           
     */
    public function authenticate(Request $request)
    {
    	$credentials = $request->only('email', 'password');

    	try {
    		if (! $token = JWTAuth::attempt($credentials)) {
				return $this->errorUnauthorized();
    		}
    	} catch (JWTException $e) {
    		return $this->errorInternalError('Could not create token');
    	}

    	return response()->json(compact('token'));
    }
}
