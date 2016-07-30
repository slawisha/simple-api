<?php

namespace App\Utilities;

class PaginatorLimiter
{
	protected $maxLimit = 100;

	/**
	 * Set number of items per page
	 * 
	 * @param integer $limit 
	 */
	public function setItemsPerPage($limit = 5)
	{
		if($limit > $this->maxLimit) $limit = $this->maxLimit;

		return $limit;
	}

	/**
	 * Set maximum items per page
	 * @param [type] $maxLimit 
	 */
	public function setMaxLimit($maxLimit)
	{
		$this->maxLimit = $maxLimit;
	}
}