<?php
	// TubeKit Beta 4
	// http://www.tubekit.org
	// Author: Chirag Shah
	// Date: 08/08/2009
	// This script developed by Chirag Shah is licensed under a 
	// Creative Commons Attribution-Noncommercial-Share Alike 3.0 United States License.

	$host = "localhost";
	$username = "me";
	$password = "mypswd";
	$database = "yt";
	$dbh = mysql_connect($host,$username,$password) or die("Cannot connect to the database: ". mysql_error());
	$db_selected = mysql_select_db($database) or die ('Cannot connect to the database: ' . mysql_error());
	$ytVideosTable = "yt_videos";
	$ytProfilesTable = "yt_profiles";
?>
