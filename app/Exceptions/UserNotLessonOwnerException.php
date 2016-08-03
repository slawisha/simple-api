<?php

namespace App\Exceptions;

class UserNotLessonOwnerException extends \Exception
{
	protected $error;

	public function __construct($error)
	{
		$this->error = $error;
	}

	public function getError()
	{
		return $this->error;
	}
}