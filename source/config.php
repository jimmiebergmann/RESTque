<?php

class Config
{

	private $filename = "";
	private $settings = null;

	public function __construct($filename)
	{
		$this->filename = $filename;
		
		if( ($this->settings = parse_ini_file($filename, true)) === false)
		{
			throw new Exception("Failed to open config file.");
		}

	}

	public function Get($category, $name)
	{
		if(is_array($this->settings) == false)
		{
			throw new Exception("Settings file is not yet loaded.");
		}

		if(isset($this->settings[$category]) == false)
		{
			throw new Exception("Unknown category: " . $category);
		}

		if(isset($this->settings[$category][$name]) == false)
		{
			throw new Exception("Unknown setting of category \"" . $category . "\": " . $name);
		}

		return $this->settings[$category][$name];
	}

}


?>