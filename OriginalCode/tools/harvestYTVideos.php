<?php
	// TubeKit Beta 4
	// http://www.tubekit.org
	// Author: Chirag Shah
	// Date: 08/08/2009
	// This script developed by Chirag Shah is licensed under a 
	// Creative Commons Attribution-Noncommercial-Share Alike 3.0 United States License.
	
 	// Usage: php harvestYTVideos.php <input_file>
	// Requires: PHP 4, MySQL, MagpieRSS
	// <input_file> should have the list of YouTube video URLs - one per line
	// You need to create a table in your MySQL database where the data collected with
	// this script can be stored. Find database.txt file on this site for details.
	// MagpieRSS can be downloaded from http://sourceforge.net/project/showfiles.php?group_id=55691
	// Once you download and unzip it, store its location in $magpieRSSLocation
	// You also need parseRSS.php and connect.php files in the same directory as this script.
	// Open connect.php in a text editor and edit it to reflect your database settings.
	
	$magpieRSSLocation = "./magpierss-0.72"; // Change this to match your locaation (without trailing '/')
	
	require_once("$magpieRSSLocation/rss_fetch.inc");
	require_once("parseRSS.php");
    require_once("connect.php"); // This file has database connection information

	if ($argc!=2)
	{
		echo "Usage: php harvestYTVideos.php <input_file>\n";
		exit;
	}

	$t=getdate();
    $today=date('Y-m-d',$t[0]);	
	$urlListFile = trim($argv[1]);
	$fin = fopen($urlListFile, 'r');
	
	while ($yt_url = fgets($fin))
	{
		$yt_url = trim($yt_url);
		echo "Processing $yt_url\n";
		$videoID = substr($yt_url,31,11);

		$query = "SELECT * from " . $ytVideosTable . " WHERE yt_id='$videoID'";
		$result = mysql_query($query) or mysql_error();
		$num_rows = mysql_num_rows($result);

		// Only if there wasn't already a video with the same ID, process further                                                                                              
		if ($num_rows == 0)					  
		{
			$feedURL = "http://gdata.youtube.com/feeds/api/videos/$videoID";
			$entry = simplexml_load_file($feedURL);
			$video = parseVideoEntry($entry);
	
			$timestamp = time();
	
			// We got everything. Now find out what we need to store.
			$title = addslashes($video->title);
			$description = addslashes($video->description);
			$username = addslashes($video->username);
			$published = $video->published;
			$duration = $video->length;
			$category = $video->category;
			$keywords = addslashes($video->keywords);
			$videoURL = $video->watchURL;
			$thumbURL = 'http://i1.ytimg.com/vi/' . $videoID . '/1.jpg';
			$viewCount = $video->viewCount;
			$numrating = $video->numrating;
			$rating = $video->rating;
			$commentsCount = $video->commentsCount;
			$favoriteCount = $video->favoriteCount;
			
			$query = "INSERT INTO " . $ytVideosTable . " VALUES('','$videoID','$title','$description','$username','$published','$duration','$category','$keywords','$videoURL','$thumbURL','$viewCount','$numrating','$rating','$commentsCount','$favoriteCount','$today')";
			$result = mysql_query($query) or mysql_error();
		} // if ($num_rows == 0)		
	} // while ($yt_url = fgets($fin))	
?>
