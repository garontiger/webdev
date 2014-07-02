<?php

    $dir    = dirname(__FILE__);

	if ($handle = opendir($dir)) {
    echo "Directory handle: $handle<br>";
    echo "Entries:<br>";
    echo "dir path : $dir<br>";


    function isEmptyDir($dir){ 
    	
    	$files = @scandir($dir);
    	// echo $dir."||count => ".count($files)."<br>"; 
	    return (($files = @scandir($dir)) && count($files) > 1); 
	} 


    /* This is the correct way to loop over the directory. */
    $filenames = readdir($handle);

    // find filename
    foreach (glob("*.php") as $filenames) {

    	if (preg_match("*",$filenames)){ 
         echo "$filenames size " . filesize($filenames) . "<br>";
     	}
		 
	}
	

	// if(isEmptyDir($dir."/".$entry)){
	// 	// in directory
	// 	echo "<br>$entry";
	// }else{
		
	// }
        
    

 

    closedir($handle);
}


?>