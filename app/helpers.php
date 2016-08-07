<?php

if ( !function_exists('check_users_token'))
{
	function check_users_token()
	{
		app('App\User')->getAuthenticatedUser();
	}
}