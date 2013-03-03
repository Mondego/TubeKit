<?php
	// TubeKit Beta 4
	// http://www.tubekit.org
	// Author: Chirag Shah
	// Date: 08/08/2009
	// This script developed by Chirag Shah is licensed under a 
	// Creative Commons Attribution-Noncommercial-Share Alike 3.0 United States License.

	function parseVideoEntry($entry) 
	{      
	  $obj= new stdClass;
  
	  // get nodes in media: namespace for media information
	  $media = $entry->children('http://search.yahoo.com/mrss/');
	  $obj->title = $media->group->title;
	  $obj->description = $media->group->description;
	  $obj->category = $media->group->category;
	  $obj->keywords = $media->group->keywords;

	  // get video player URL
	  $attrs = $media->group->player->attributes();
	  $obj->watchURL = $attrs['url']; 
  
	  // get video thumbnail
	  $attrs = $media->group->thumbnail[0]->attributes();
	  $obj->thumbnailURL = $attrs['url']; 
     
	  // get <yt:duration> node for video length
	  $yt = $media->children('http://gdata.youtube.com/schemas/2007');
	  $attrs = $yt->duration->attributes();
	  $obj->length = $attrs['seconds']; 
  
	  // get <yt:stats> node for viewer statistics
	  $yt = $entry->children('http://gdata.youtube.com/schemas/2007');
	  $attrs = $yt->statistics->attributes();
	  $obj->viewCount = $attrs['viewCount'];
	  $obj->favoriteCount = $attrs['favoriteCount'];

	  // get <yt:stats> node for viewer statistics
	  $yt = $entry->children('http://gdata.youtube.com/schemas/2007');
	  $obj->username = $entry->author->name; 
	  $obj->published = $entry->published;
  
	  // get <gd:rating> node for video ratings
	  $gd = $entry->children('http://schemas.google.com/g/2005'); 
	  if ($gd->rating) { 
	    $attrs = $gd->rating->attributes();
	    $obj->rating = $attrs['average']; 
		$obj->numrating = $attrs['numRaters'];
	  } else {
	    $obj->rating = 0;         
	  }
    
	  // get <gd:comments> node for video comments
	  $gd = $entry->children('http://schemas.google.com/g/2005');
	  if ($gd->comments->feedLink) { 
	    $attrs = $gd->comments->feedLink->attributes();
	    $obj->commentsURL = $attrs['href']; 
	    $obj->commentsCount = $attrs['countHint']; 
	  }
  
	  // get feed URL for video responses
	  $entry->registerXPathNamespace('feed', 'http://www.w3.org/2005/Atom');
	  $nodeset = $entry->xpath("feed:link[@rel='http://gdata.youtube.com/schemas/
	  2007#video.responses']"); 
	  if (count($nodeset) > 0) {
	    $obj->responsesURL = $nodeset[0]['href'];      
	  }
     
	  // get feed URL for related videos
	  $entry->registerXPathNamespace('feed', 'http://www.w3.org/2005/Atom');
	  $nodeset = $entry->xpath("feed:link[@rel='http://gdata.youtube.com/schemas/
	  2007#video.related']"); 
	  if (count($nodeset) > 0) {
	    $obj->relatedURL = $nodeset[0]['href'];      
	  }

	  // return object to caller  
	  return $obj;      
	}
?>