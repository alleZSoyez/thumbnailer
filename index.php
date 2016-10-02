<?php
	// get files
	$files = scandir(".");
	
	// start table & first row
	echo "<table style=\"width:100%; margin:auto; text-align:center;\">
			<tr>";
	
	// set our defaults... change rowlimit & size if you want those different, the rest is fine as it is
	$rowlimit = 10;
	$size = 100;
	
	$counter = 0;
	$original;
	
	foreach ( $files as $images ) {
		
		// only take images
		if ( preg_match("/$.jpeg|.jpg|.png|.gif/i", $images) ) {
			
			// counting
			$counter += 1;
			
			// here we want to get the info for our current image in order to
			// create our base thumbnail. we want to check for the dimensions as follows:
			// if the width is bigger, max width 100
			// if the height is bigger, max height 100
			// if the whole image is smaller than 100x100 don't resize it at all
			
			// get image info. [0] = w, [1] = h, ignore the rest
			$thissize = getimagesize($images);
			
			// now we want to compare sizes and do our math.
			
			// w >= h
			if ($thissize[0] >= $thissize[1]) {
				$w = $size;
				$h = ($thissize[1] * $size) / $thissize[0];
			}
			// h >= w
			if ($thissize[1] >= $thissize[0]) {
				$w = ($thissize[0] * $size) / $thissize[1];
				$h = $size;
			}
			// w & h <= max
			if ($thissize[0] <= $size && $thissize[1] <= $size) {
				$w = $thissize[0];
				$h = $thissize[1];
			}
			
			// produce thumbnail if it does not exist already
			if (!file_exists($_SERVER['DOCUMENT_ROOT']."/thumbs/$images")) {
				
				// load images
				if ( mime_content_type($images) == "image/jpeg" ) { // jpeg or jpg
					$original = imagecreatefromjpeg($images);
				}
				
				if ( mime_content_type($images) == "image/png" ) { // png
					$original = imagecreatefrompng($images);
				}
				
				if ( mime_content_type($images) == "image/gif" ) { // gif
					$original = imagecreatefromgif($images);
				}
				
				// create base thumbnail, prime it for transparency, copy our original image,
				// save the thumbnail to disk & garbage collect
				$thumbnail = imagecreatetruecolor($w,$h);
				imagealphablending($thumbnail, false);
				imagesavealpha($thumbnail,true);
				imagecopyresampled($thumbnail,$original,0,0,0,0,$w,$h,$thissize[0],$thissize[1]);
				imagepng($thumbnail,$_SERVER['DOCUMENT_ROOT']."/thumbs/$images");
				imagedestroy($thumbnail);	
				
			}

			// display as td with link to full size
			echo "<td>
					<a href=\"$images\" target=\"_blank\">
						<img src=\"/thumbs/$images\">
					</a>
				</td>";
		}
		
		// start a new row when we've reached our limit per row
		if ( $counter % $rowlimit == 0) {
			echo "</tr><tr>";
		}
		
	}
	
	// end table & last row
	echo "</tr>
		</table>";

?>