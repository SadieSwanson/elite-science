<?php

require "vendor/autoload.php";
use GuzzleHttp\Client;

$systemListing = file('systemsSourceSol600.txt');
// $systems = file('systems-mini.txt');
// $write = 'bodies.csv';
$write = 'systemsSol600.csv';

// Clear contents of the file before writing new data.
file_put_contents($write, '', LOCK_EX);

$properties = [
  'name',
  'updateTime',
  'type',
  'subType',
  'distanceToArrival',
  'isMainStar',
  'age',
  'luminosity',
  'surfaceTemperature',
];


file_put_contents($write, "systemName,", FILE_APPEND | LOCK_EX);
foreach ($properties as $prop) {
  file_put_contents($write, $prop . ",", FILE_APPEND | LOCK_EX);
}
file_put_contents($write, "\r\n", FILE_APPEND | LOCK_EX);

$apisystemv1 = new Client([
  'base_uri' => 'https://www.edsm.net/api-system-v1/',
  'connect_timeout' => 0,
  'read_timeout' => 0,
  'timeout' => 0,
]);

$apiv1 = new Client([
  'base_uri' => 'https://www.edsm.net/api-v1/cube-systems',
  'connect_timeout' => 0,
  'read_timeout' => 0,
  'timeout' => 0,
]);

// $system = 'Savantzil';
//
// $response = $apiv1->request('GET', 'cube-systems', [
//     'query' => [
//       'systemName' => $system,
//       'size' => 200,
//     ]
// ]);
//
// $body = $response->getBody();
// $systems = json_decode($body);
$systems = $systemListing;

foreach ($systems as $system_num => $system) {
  $systemName = trim($system);
  // $systemName = trim($system->name);

  // file_put_contents($write, $systemName, FILE_APPEND | LOCK_EX);
  // file_put_contents($write, "\r\n", FILE_APPEND | LOCK_EX);

  $response = $apisystemv1->request('GET', 'bodies', [
      'query' => ['systemName' => $systemName]
  ]);

  $body = $response->getBody();
  $obj = json_decode($body);
  $bodies = $obj->bodies;

  print("Checking system.. " . $systemName . "\r\n");
  foreach ($bodies as $systemBody) {
    if ($systemBody->type === 'Star' && $systemBody->subType === 'Neutron Star') {
      file_put_contents($write, $systemName . ",", FILE_APPEND | LOCK_EX);
      foreach ($properties as $prop) {
        file_put_contents($write, $systemBody->$prop . ",", FILE_APPEND | LOCK_EX);
      }
      file_put_contents($write, "\r\n", FILE_APPEND | LOCK_EX);
    }
  }
}
