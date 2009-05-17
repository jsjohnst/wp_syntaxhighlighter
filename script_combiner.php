<?php

header("Expires: " . date("r", time() + ( 60 * 60 * 24 * 365 ) ) ); // Expires in 1 year
header("Content-Type: application/x-javascript");

$legacy = false;
$brushes = "all";

if(isset($_REQUEST["legacy"]) && $_REQUEST["legacy"] == 1) {
	$legacy = true;
}

if(isset($_REQUEST["brushes"])) {
	$brushes = strtolower($_REQUEST["brushes"]);
}

echo "\n\n// scripts/shCore.js\n";
readfile("scripts/shCore.js");

if($legacy) {
	echo "\n\n// scripts/shLegacy.js\n";
	readfile("scripts/shLegacy.js");
}

foreach(glob("scripts/shBrush*.js") as $filename) {
	if($brushes != "all") {
		$brush = strtolower(substr($filename, strpos($filename, "Brush") + 5, -3)); 
		if(strstr($brushes, $brush) === false) {
			echo "\n\n// skipping " . $filename . "\n";
			continue;
		}
	}
	echo "\n\n// " . $filename . "\n";
	readfile($filename);
}

