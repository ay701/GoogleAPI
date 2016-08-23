<?php
	// Include the Google Analytics data import class
	include_once 'GAnalytics.php';
	require "gapi.class.php";
	
	$profileID = "68645509";
	
	// Set up your Google Analytics credentials
    $email = $_POST['email'];
	$passwd = $_POST['passwd'];
	$gaUrl = "";

	// Keep your connection data into a config array
	$config = array('email'      => $email,
					'password'   => $passwd,
					'requestUrl' => $gaUrl,
	);

	// Create a new GAnalytics object
	$ga = new GAnalytics($config);

	try {
		// Call the Google Analytics API check login validation
		if(!$ga->login_AZ()) 
			exit("<br><font color=red style='font-weight:bold'>Login failed. Refresh & Try again.</font>");

	} catch (Exception $e) {

		// Log your error here
		echo "GAnalytics Connection error ({$e->getCode()}): {$e->getMessage()}";
	}