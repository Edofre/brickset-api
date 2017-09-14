<?php

// Load data
$sets = file('../data/sets.txt');

// API auth details
$apiKey = 'x';
$userHash = 'x';

$url = 'https://brickset.com/api/v2.asmx/getSets?';
$queryDetails = [
	'apiKey'     => $apiKey,
	'userHash'   => $userHash,
	'setNumber'  => '',
	'query'      => '',
	'theme'      => '',
	'subtheme'   => '',
	'year'       => '',
	'owned'      => '',
	'wanted'     => '',
	'pageNumber' => '',
	'pageSize'   => '',
	'orderBy'    => '',
	'userName'   => '',
];

// Open te file we'll write the prices to
$pricesFile = fopen("../output/setPrices.txt", "w") or die("Unable to open file!");

$curl = curl_init();
foreach ($sets as &$set) {
	// Add a -1 to the setname because we need to specify the version
	$set = trim($set) . '-1';

	$queryDetails['setNumber'] = $set;
	$query = http_build_query($queryDetails);
	curl_setopt_array($curl, [
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL            => $url . $query,
	]);
	$resp = curl_exec($curl);
	$xml = simplexml_load_string($resp);
	if ($xml === false) {
		echo "Failed loading XML: ";
		foreach (libxml_get_errors() as $error) {
			echo "<br>", $error->message;
		}
	} else {
		// Fetch variables we need to write
		$setName = (string)$xml->sets->name;
		$setNumber = (string)$xml->sets->number;
		$setPrice = (float)$xml->sets->EURetailPrice;

		// And write them to the file
		fwrite($pricesFile, "$setNumber \t $setPrice \t $setName \n");
	}
}
curl_close($curl);

fclose($pricesFile);

var_dump("Done writing to /output/setPrices.txt");
?>