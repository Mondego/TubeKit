<?php
	// TubeKit Beta 4
	// http://www.tubekit.org
	// Author: Chirag Shah
	// Date: 08/08/2009
	// This script developed by Chirag Shah is licensed under a 
	// Creative Commons Attribution-Noncommercial-Share Alike 3.0 United States License.
	
    // Usage: php extractYTVideoURLs.php <input_file> <output_file>
	// Requires: PHP 4
	// <input_file> should have the list of YouTube URLs - one per line
	
	if ($argc!=3)
	{
		echo "Usage: php extractYTVideoURLs.php <input_file> <output_file>\n";
		exit;
	}

	$urlListFile = trim($argv[1]);
	$outputFile = trim($argv[2]);
	$fin = fopen($urlListFile, 'r');
	$fout = fopen($outputFile, 'w');
	
	while ($yt_url = fgets($fin))
	{
		$yt_url = trim($yt_url);
		echo "Processing $yt_url\n";
		$html = file_get_contents($yt_url);
		$fh = fopen('yt.temp', 'w');
		fwrite($fh, $html);
		fwrite($fh, "\n");
		fclose($fh);
		$fh = fopen('yt.temp', 'r');
		$previousURL = "";
		while ($line = fgets($fh))
		{
			$line = trim($line);
			if (preg_match("/ href=\"\/watch\?/", $line))
		   	{
				list($t1,$t2) = explode(" href=\"/watch?v=",$line);
				// Get the YT id (11 characters)
				$ytID = substr($t2, 0, 11);
				$url = "http://www.youtube.com/watch?v=".$ytID;
				if ($url != $previousURL)
				{
					fwrite ($fout, "$url\n");
					$previousURL = $url;
				}
			} // if (preg_match("/<a href=\"\/watch\?/", $line))
		} // while ($line = fgets($fh))
	} // while ($yt_url = fgets($fin))
	
	fclose($fin);
	fclose($fout);
?>
