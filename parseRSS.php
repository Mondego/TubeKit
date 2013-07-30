<?php
	// TubeKit Beta 4
	// http://www.tubekit.org
	// Author: Chirag Shah
	// Date: 10/22/2010
	function parseVideoEntry($entry) {    
	  $obj= new stdClass;  
	  if ($entry) {
	     	  
	     	  //echo " Value for entry from the collectOnce.php" ."\n";
		  //echo '<pre>'; var_dump($entry); echo '</pre>';
		  // echo $entry . "\n";
		  // get nodes in media: namespace for media information
		  if ($media = $entry->children('http://search.yahoo.com/mrss/')) {
		  $obj->title = $media->group->title;
		  $obj->description = $media->group->description;
		  $obj->category = $media->group->category;
		  $obj->keywords = $media->group->keywords;
		  
		  // get video player URL
			  $attrs = $media->group->player->attributes();
			  $obj->watchURL = $attrs['url']; 
		//	  echo " URL DUMP";
		//	  echo '<pre>'; var_dump($obj->watchURL); echo '</pre>';
		//	  echo " End---------------------------------". "\n";
  
			  // get video thumbnail
			  if ($media->group->thumbnail[0]) {
				  $attrs = $media->group->thumbnail[0]->attributes();
				  $obj->thumbnailURL = $attrs['url']; 			  
			  }
     
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
			  $ratingAttrs = $yt->rating->attributes();
			  $obj->username = $entry->author->name; 
			  $obj->published = $entry->published;
			  // ---- Rating Information -----
			  $obj->dislikes = $ratingAttrs['numDislikes'];
			  $obj->likes = $ratingAttrs['numLikes'];
  
			  // get <gd:rating> node for video ratings
			  $gd = $entry->children('http://schemas.google.com/g/2005'); 
			  $obj->numrating = 0;
			  $obj->rating = 0;
			  if ($gd->rating) { 
			    $attrs = $gd->rating->attributes();
			    $obj->rating = $attrs['average']; 
			    $obj->numrating = $attrs['numRaters'];
			  } 
    
			  // get <gd:comments> node for video comments
			  $gd = $entry->children('http://schemas.google.com/g/2005');
			  if ($gd->comments->feedLink) { 
			    $attrs = $gd->comments->feedLink->attributes();
			    $obj->commentsURL = $attrs['href']; 
			    $commentsCount = 0;
			    if(isset($attrs['countHint']))
			    {
				$obj->commentsCount = $attrs['countHint']; 
			    }
			    else
			    {
				$obj->commentsCount = 0;
			    }
			  }

			  // get feed URL for video responses
			  $entry->registerXPathNamespace('feed', 'http://www.w3.org/2005/Atom');
			  $nodeset = $entry->xpath("feed:link[@rel='http://gdata.youtube.com/schemas/2007#video.responses']"); 
			  $obj->responseCount =	count($nodeset);
			  $obj->responsesURL = "";
			  if (count($nodeset) > 0) {
			    $obj->responsesURL = $nodeset[0]['href'];      
			  }

			  // get feed URL for related videos
                          $entry->registerXPathNamespace('feed', 'http://www.w3.org/2005/Atom');
                          $nodeset = $entry->xpath("feed:link[@rel='http://gdata.youtube.com/schemas/2007#video.related']");
                          if (count($nodeset) > 0) {
			     $obj->relatedURL = $nodeset[0]['href'];
                          }
     	}
		  // return object to caller  
		  return $obj;      
		}
		else {
			return NULL;
		}
	}
?>