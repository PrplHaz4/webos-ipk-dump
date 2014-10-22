<?php
$token = 'PALM_PROFILE_TOKEN';
$deviceId = 'DEVICE_PROFILE_NDUID';

// Save "Installed Apps" from Impostah as plaintext .json
$inputFile = 'ipkdump.json';

function downloadIPK($token, $deviceId, $IPKUrl)
{
	$savefile = getAppFilename($IPKUrl);
	$outputfile = "./cache/{$savefile}";
	
	echo "{$savefile}";
	
	if(!file_exists("{$outputfile}")) {
		$cmd = "wget -q \"$IPKUrl\" -O {$outputfile} --header=\"Auth-Token: {$token}\" --header=\"Device-Id: {$deviceId}\"";
		exec($cmd);
		echo "...saved!\r\n";
	} else {
		echo "...already cached!\r\n";
	}
}

function getAppFilename($IPKUrl)
{
	return basename(parse_url($IPKUrl, PHP_URL_PATH));
}

function readIPKjson($inputFile)
{
	$contents = file_get_contents($inputFile); 
	$contents = utf8_encode($contents); 
	$results = json_decode($contents);

	return $results;
}

function createZip($appList, $deviceId)
{
	$z = new ZipArchive();
	$z->open("ipkdump_{$deviceId}.zip", ZIPARCHIVE::CREATE);
	foreach($appList as $app) {
		$appFilename = getAppFilename($app->appLocation);
		$z->addFile("./cache/{$appFilename}", $appFilename);
	}
	$z->close();
	
	echo "\r\nDone saving ipkdump_{$deviceId}.zip\r\n";
}

function createTar($appList, $deviceId)
{
	$a = new PharData("ipkdump_{$deviceId}.tar");

	foreach($appList as $app) {
		$appFilename = getAppFilename($app->appLocation);
		$a->addFile("./cache/{$appFilename}", $appFilename);
	}
	
	echo "\r\nDone saving ipkdump_{$deviceId}.tar\r\n";
}

function createTarExec($appList, $deviceId)
{
	$files = "";
	$a = new PharData("ipkdump_{$deviceId}.tar");

	foreach($appList as $app) {
		$appFilename = getAppFilename($app->appLocation);
		$files = $files . " ./cache/{$appFilename}";
	}

	$cmd = "tar -cvf ipkdump_{$deviceId}.tar{$files}";
	exec($cmd);
	echo "\r\nDone saving ipkdump_{$deviceId}.tar\r\n";
}

if (!file_exists('./cache')) {
    mkdir('./cache', 0777, true);
}

$appList = readIPKjson($inputFile);

foreach($appList as $app) {
	echo "\r\n{$app->title}\r\n";
	downloadIPK($token, $deviceId, "{$app->appLocation}");
}

echo "\r\nCreating archive of all your IPKs...";

// Fastest
createTarExec($appList, $deviceId);

// Only use these if exec is not available
// Medium
// createTar($appList, $deviceId);

// Slowest
//createZip($appList, $deviceId);
?>