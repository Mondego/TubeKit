<?php   
	// TubeKit Beta 4
	// http://www.tubekit.org
	// Author: Chirag Shah
	// Date: 08/08/2009
	// This script developed by Chirag Shah is licensed under a 
	// Creative Commons Attribution-Noncommercial-Share Alike 3.0 United States License.

	// Usage: php harvestYTProfiles.php
	// Requires: PHP 4, MySQL
	// You need to create a table in your MySQL database where the data collected with
	// this script can be stored. Find database.txt file on this site for details.
	// You also need connect.php file in the same directory as this script.
	// Open connect.php in a text editor and edit it to reflect your database settings.
		
	// Place to store the profile pages (e.g., /home/me/ytprofiles)
	// Do not include the trailing '/'
	$profileDirectory = ".";
	
	$t = getdate();
    $date = date('Y-m-d', $t[0]);

    // read 'author profile feed' into SimpleXML object
   	// parse and store author bio
	require_once("connect.php");
    $query = "SELECT distinct username FROM ".$ytVideosTable; // Formulate the query
	$results = mysql_query($query) or die(" ". mysql_error()); // Execute the query, get the results
	while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) // Go record by record
	{	
		$userName = $line['username'];
		$query1 = "SELECT * from " . $ytProfilesTable . " WHERE username='$userName'";
		$result1 = mysql_query($query1) or mysql_error();
		$num_rows = mysql_num_rows($result1);

		// Only if there wasn't already a profile with the same username, process further                                                                                              
		if ($num_rows == 0)					  
		{
			echo "Processing user $userName\n";
			$url = "http://www.youtube.com/user/".$userName;
			$content = file_get_contents($url);
			if ($content !== false) 
			{
				$fileName = $profileDirectory."/".$userName.".profile";
				$fh = fopen($fileName, 'w');
				fwrite($fh, $content);
				fclose($fh);
			} // if ($content !== false) 
			$video->authorURL = "http://gdata.youtube.com/feeds/api/users/".$userName;
			$authorFeed = simplexml_load_file($video->authorURL);
			if ($authorFeed)
			{
			   	$authorData = $authorFeed->children('http://gdata.youtube.com/schemas/2007');
				$firstName = addslashes($authorData->firstName);
				$lastName = addslashes($authorData->lastName);
				$gender = addslashes($authorData->gender);
			   	$age = $authorData->age;
				$hometown = addslashes($authorData->hometown);
			    $location =  addslashes($authorData->location);  
			  	$occupation = addslashes($authorData->occupation);
				$company = addslashes($authorData->company);
				$school = addslashes($authorData->school);
				$hobbies = addslashes($authorData->hobbies);
				$movies = addslashes($authorData->movies);
				$music = addslashes($authorData->music);
				$books = addslashes($authorData->books);
				$query = "INSERT INTO $ytProfilesTable  VALUES('','$userName','$firstName','$lastName','$gender','$age','$hometown','$location','$occupation','$company','$school','$hobbies','$movies','$music','$books','$date')";
				$result = mysql_query($query) or die(" ". mysql_error());
			} // if ($authorFeed)
		} // if ($num_rows == 0)	
	} // while ($line = mysql_fetch_array($results, MYSQL_ASSOC))
?>
