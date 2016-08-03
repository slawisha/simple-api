<?php

namespace App\Traits;

trait PaginatorLimiterTrait
{
	protected $maxLimit = 100;
	protected $defaultLimit = 5;

	/**
	 * Set number of items per page
	 * 
	 * @param integer $limit 
	 */
	public function setItemsPerPage($limit = 5)
	{
		if(is_null($limit)) return $this->defaultLimit;
		
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