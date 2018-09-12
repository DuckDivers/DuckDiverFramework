<?php 

/**
 * Current theme I/O files
 */

// Check to see if Child Theme is in use
if (is_child_theme()) {
    $directory = get_stylesheet_directory();
}
else {
    $directory = get_template_directory(); 
}
	$inputFile  = $directory . '/custom.less';
	$outputFile = $directory . '/custom.css';

// load the cache

  $cacheFile = $inputFile.".cache";

  if (file_exists($cacheFile)) {
    $cache = unserialize(file_get_contents($cacheFile));
  } else {
    $cache = $inputFile;
  }

  $less = new lessc;


try {
		// create a new cache object, and compile
		$newCache = $less->cachedCompile($cache);

		// the next time we run, write only if it has updated
		if ( !is_array($cache) || $newCache["updated"] > $cache["updated"] ) {
			file_put_contents($cacheFile, serialize($newCache));
			file_put_contents($outputFile, $newCache['compiled']);
		}
	} catch (Exception $ex) {
		echo "lessphp fatal error: ".$ex->getMessage();
	}
