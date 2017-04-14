<?php

class Request
{

	private $method = "";
	private $resources = null;
	private $full_resource = "";

	public function __construct()
	{
		$request_url = $_SERVER['REQUEST_URI'];
		$this->full_resource = explode('?', $request_url)[0];
		$this->resources = explode('/', $this->full_resource);
		if(count($this->resources) > 1)
		{
			if(strlen($this->resources[0]) == 0)
			{
				unset($this->resources[0]);
			}
		}

		$this->method = $_SERVER['REQUEST_METHOD'];
	}

	public function GetMethod()
	{
		return $this->method;
	}

	public function GetResources()
	{
		return $this->resources;
	}

	public function GetFullResource()
	{
		return $this->full_resource;
	}

}

?>