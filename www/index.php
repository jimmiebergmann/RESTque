<?php

// Include files.
require_once("../source/config.php");
require_once("../source/database.php");
require_once("../source/request.php");


// Load config
try
{
	$config = LoadConfig("../server.ini");
}
catch(Exception $e)
{
	die("Failed to load config: " . $e->getMessage());
}


// Load request manager
$request = new Request();
$method = $request->GetMethod();
$resources = $request->GetResources();
$full_resource = $request->GetFullResource();


if($method == "POST")
{
	if(count($resources) == 1)
	{
		if($resources[1] == "login")
		{
			HandleRequestLogin();
		}
		else if($resources[1] == "push")
		{
			HandleRequestPush();
		}
	}

	HandleUnknownResource();
}
else if($method == "GET")
{
	if(count($resources) == 1)
	{
		if($resources[1] == "pull")
		{
			HandleRequestPush();
		}
	}

	HandleUnknownResource();
}
else
{
	die('{"error":"Method not supported: ' . $method . '"}');
}


// Function for loading and validating config file.
function LoadConfig($filename)
{
	$config = new Config($filename);

	// Validate config file.
	try
	{
		$config->Get("database", "server");
		$config->Get("database", "port");
		$config->Get("database", "database");
		$config->Get("database", "user");
		$config->Get("database", "password");
	}
	catch(Exception $e)
	{
		throw new Exception("Failed to validate config: " . $e->getMessage());
	}


	return $config;
}

// Request handle functions
function HandleRequestLogin()
{
	//$_SERVER['HTTP_XXXXXX_XXXX'];

	die('{"token":"ASDJASDA7SFAF67AS67FASD"}');
}

function HandleRequestPush()
{
	
}

function HandleRequestPull()
{
	
}

function HandleUnknownResource()
{
	global $full_resource;
	die('{"error":"Unknown resource \\"'. $full_resource .'\\""}');
}

?>