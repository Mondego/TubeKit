<?php
	// TubeKit Beta 4
	// http://www.tubekit.org
	// Author: Chirag Shah
	// Date: 08/08/2009
	// This script developed by Chirag Shah is licensed under a 
	// Creative Commons Attribution-Noncommercial-Share Alike 3.0 United States License.
	
    require_once("connect.php"); // This file has database connection information
	ini_set("memory_limit","100M");
    
	if ($argc!=3)
	{
		echo "Usage: php collectComments.php <yt videos table name> <comments table name>\n";
		exit;
	}
	$tableName1 = trim($argv[1]);
	$tableName2 = trim($argv[2]);
		
	$query = "SELECT * FROM $tableName1 GROUP BY yt_id"; 
	$vresult = mysql_query($query) or die(" ". mysql_error());

	while ($line = mysql_fetch_array($vresult, MYSQL_ASSOC)) {
		$ytID = $line['yt_id'];
		$videoURL = $line['video_url'];
		$id = $line['video_id'];
		
		$feedURL = "http://gdata.youtube.com/feeds/api/videos/$ytID";
		echo "$id. $feedURL\n";
		if (file_get_contents($feedURL)) {
			$entry = simplexml_load_file($feedURL);
			$obj= new stdClass;  
			if ($entry) {
				// get <gd:comments> node for video comments
				$gd = $entry->children('http://schemas.google.com/g/2005');
				if ($gd->comments->feedLink) { 
				  $attrs = $gd->comments->feedLink->attributes();
				  $obj->commentsURL = $attrs['href']; 
				  $obj->commentsCount = $attrs['countHint']; 
				}
			  
				// read 'video comments' feed into SimpleXML object
				// parse and display each comment
				if (($obj->commentsURL) && ($obj->commentsCount > 0)) {
					$maxPages = round($obj->commentsCount/50);
					for ($page=1; $page<=$maxPages; $page++) {
						$startIndex = ($page-1)*50+1;
						$commentsURL = $obj->commentsURL . "?v=2&max-results=50&start-index=$startIndex";
						echo "\t$commentsURL\n";
						if (file_get_contents($commentsURL)) {
						    $commentsFeed = simplexml_load_file($commentsURL);    
						    foreach ($commentsFeed->entry as $comment) {
								foreach ($comment->author as $author) {
									$commentText = $comment->content;
									$commentText = addslashes($commentText);
									$query1 = "INSERT INTO $tableName2 VALUES('','$videoURL','$author->name','$commentText')";
									$result1 = mysql_query($query1) or die(" ". mysql_error());
								} // foreach ($comment->author as $author)		
						    } // foreach ($commentsFeed->entry as $comment)
						} // if (file_get_contents($obj->commentsURL))
					} // for ($page=1; $page<=$maxPages; $page++)
				} // if (($obj->commentsURL) && ($obj->commentsCount > 0))
			} // if ($entry)
		}
	}
?>
