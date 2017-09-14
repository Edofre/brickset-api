<?php
$names = file('../data/sets.txt');

foreach ($names as &$name) {
	$name = trim($name) . '-1';
}

$url = 'https://brickset.com/api/v2.asmx/getSets?';
$query = http_build_query([
	'apiKey'     => 'XXX',
	'userHash'   => 'XXX',
	'setNumber'  => 'XXX',
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
]);

var_dump($query);
var_dump($url);

$curl = curl_init();
curl_setopt_array($curl, [
	CURLOPT_RETURNTRANSFER => 1,
	CURLOPT_URL            => $url . $query,
]);
$resp = curl_exec($curl);
curl_close($curl);


$xml = simplexml_load_string($resp);
if ($xml === false) {
	echo "Failed loading XML: ";
	foreach (libxml_get_errors() as $error) {
		echo "<br>", $error->message;
	}
} else {
	var_dump($xml);
}
?>