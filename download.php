<?php 
	
	// init zip
	$zip = new ZipArchive();
	
	// grab files (literally just copied this from the other script lol)
	$files = scandir(".");
	
	// get zip ready
	if (!$zip->open("images.zip", ZipArchive::CREATE)) {
		echo "<script type=\"text/javascript\">alert(\"Something went wrong while creating zip file.\");";
	}
	else {
				
		foreach ( $files as $images ) {
			// only take images
			if ( preg_match("/$.jpeg|.jpg|.png|.gif/i", $images) ) {
			
				// insert files
				$zip->addFile($images);
				
				// download
				header("Location: ./images.zip");

			}
		}
	}

?>
