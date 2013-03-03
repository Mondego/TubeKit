<?php
	// TubeKit Beta 4
	// http://www.tubekit.org
	// Author: Chirag Shah
	// Date: 08/08/2009
	// This script developed by Chirag Shah is licensed under a 
	// Creative Commons Attribution-Noncommercial-Share Alike 3.0 United States License.
	
    // Usage: php downloadYTVideos.php <input_file>
	// Requires: PHP 4, Python 2.4
	// <input_file> should have the list of YouTube video URLs - one per line
	// This script assumes you have 'youtube-dl' available at $ytdlLocation.
	// 'youtube-dl' can be downloaded from http://www.arrakis.es/~rggi3/youtube-dl/
	// The videos downloaded from running this PHP script will be stored in
	// the current directory.

	$ytdlLocation = "."; // '.' means current directory. Do not put a trailing '/'.

	if ($argc!=2)
	{
		echo "Usage: php downloadYTVideos.php <input_file>\n";
		exit;
	}
	$urlListFile = trim($argv[1]);
	$fin = fopen($urlListFile, 'r');
	echo "Processing $urlListFile...\n\n";
	
	while ($line = fgets($fin))
	{
		$command = "python " . $ytdlLocation . "/youtube-dl ". $line;
		system($command);
	}
	fclose($fin);
?>
