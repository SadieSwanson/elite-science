<?php

require "vendor/autoload.php";
use GuzzleHttp\Client;

// $systems = file('systems.txt');
$systems = file('systems.txt');
$write = 'systemsAllegiance.csv';

// Clear contents of the file before writing new data.
file_put_contents($write, '', LOCK_EX);

$properties = [
  'name',
  'allegiance',
];

foreach ($properties as $prop) {
  file_put_contents($write, $prop . ",", FILE_APPEND | LOCK_EX);
}
file_put_contents($write, "\r\n", FILE_APPEND | LOCK_EX);

$client = new Client([
  'base_uri' => 'https://www.edsm.net/api-v1/',
  'connect_timeout' => 0,
  'read_timeout' => 0,
  'timeout' => 0,
]);

foreach ($systems as $system_num => $system) {
  $system = trim($system);

  $response = $client->request('GET', 'system', [
      'query' => [
        'systemName' => $system,
        'showId' => 1,
        'showPermit' => 1,
        'showInformation' => 1,
      ]
  ]);

  $body = $response->getBody();
  $system = json_decode($body);

  // if (property_exists($system, 'information')) {
  //   var_dump($system->information->allegiance);
  // }
  var_dump($system);

    file_put_contents($write, $system->name . ",", FILE_APPEND | LOCK_EX);
    file_put_contents($write, $system->information->allegiance . ",", FILE_APPEND | LOCK_EX);

    file_put_contents($write, "\r\n", FILE_APPEND | LOCK_EX);

}
