<?php

require "vendor/autoload.php";
use GuzzleHttp\Client;

$systems = file('systems.txt');
// $systems = file('systems-mini.txt');
$write = 'bodies.csv';

// Clear contents of the file before writing new data.
file_put_contents($write, '', LOCK_EX);

$properties = [
  'name',
  'updateTime',
  'type',
  'subType',
  'distanceToArrival',
  'isLandable',
  'gravity',
  'earthMasses',
  'radius',
  'surfaceTemperature',
  'volcanismType',
  'atmosphereType',
  'terraformingState',
  'orbitalEccentricity',
  'orbitalInclination',
  'argOfPeriapsis',
  'rotationalPeriod',
  'rotationalPeriodTidallyLocked',
  'axialTilt',
  'orbitalPeriod',
  'semiMajorAxis',
];

$starOnlyProperties = [
  'isMainStar',
  'isScoopable',
  'age',
  'luminosity',
  'surfaceTemperature',
];

$materials = [
  'Antimony',
  'Arsenic',
  'Boron',
  'Cadmium',
  'Carbon',
  'Chromium',
  'Germanium',
  'Iron',
  'Lead',
  'Manganese',
  'Mercury',
  'Molybdenum',
  'Nickel',
  'Niobium',
  'Phosphorus',
  'Polonium',
  'Rhenium',
  'Ruthenium',
  'Selenium',
  'Sulphur',
  'Technetium',
  'Tellurium',
  'Tin',
  'Tungsten',
  'Vanadium',
  'Yttrium',
  'Zinc',
  'Zirconium',
];

$solidComposition = [
  'Ice',
  'Metal',
  'Rock',
];

$atmosphereComposition = [
  'Ammonia',
  'Argon',
  'Carbon dioxide',
  'Helium',
  'Hydrogen',
  'Iron',
  'Methane',
  'Neon',
  'Nitrogen',
  'Oxygen',
  'Silicates',
  'Sulphur dioxide',
  'Water',
];

$ringSystemProperties = [
  'name',
  'updateTime',
  'type',
  'subType',
];

$ringProperties = [
  'name',
  'type',
  'mass',
  'innerRadius',
  'outerRadius',
];

// ["rings"]=>
// array(1) {
//   [0]=>
//   object(stdClass)#250 (5) {
//     ["name"]=>
//     string(20) "HIP 19026 B 4 A Ring"
//     ["type"]=>
//     string(3) "Icy"
//     ["mass"]=>
//     int(245900000000)
//     ["innerRadius"]=>
//     int(69788)
//     ["outerRadius"]=>
//     int(113520)
//   }
// }

// ["belts"]=>
// array(1) {
//   [0]=>
//   object(stdClass)#21 (5) {
//     ["name"]=>
//     string(18) "HIP 19026 A A Belt"
//     ["type"]=>
//     string(8) "Metallic"
//     ["mass"]=>
//     int(66725000000000)
//     ["innerRadius"]=>
//     int(1386500)
//     ["outerRadius"]=>
//     int(2529900)
//   }
// }

// foreach ($ringSystemProperties as $prop) {
//   file_put_contents($write, $prop . ",", FILE_APPEND | LOCK_EX);
// }
foreach ($ringProperties as $prop) {
  file_put_contents($write, $prop . ",", FILE_APPEND | LOCK_EX);
}
// foreach ($solidComposition as $prop) {
//     file_put_contents($write, $prop . ",", FILE_APPEND | LOCK_EX);
// }
// foreach ($atmosphereComposition as $prop) {
//     file_put_contents($write, $prop . ",", FILE_APPEND | LOCK_EX);
// }
// foreach ($starOnlyProperties as $prop) {
//   file_put_contents($write, $prop . ",", FILE_APPEND | LOCK_EX);
// }
// foreach ($materials as $prop) {
//   file_put_contents($write, $prop . ",", FILE_APPEND | LOCK_EX);
// }
// file_put_contents($write, "hasRings,", FILE_APPEND | LOCK_EX);
file_put_contents($write, "\r\n", FILE_APPEND | LOCK_EX);

$client = new Client([
  'base_uri' => 'https://www.edsm.net/api-system-v1/',
  'connect_timeout' => 0,
  'read_timeout' => 0,
  'timeout' => 0,
]);

foreach ($systems as $system_num => $system) {
  $system = trim($system);

  $response = $client->request('GET', 'bodies', [
      'query' => ['systemName' => $system]
  ]);

  $body = $response->getBody();
  $obj = json_decode($body);
  $bodies = $obj->bodies;

  foreach ($bodies as $systemBody) {
    // var_dump($bodies);

    // foreach ($ringSystemProperties as $prop) {
    //   file_put_contents($write, $systemBody->$prop . ",", FILE_APPEND | LOCK_EX);
    // }
    // foreach ($solidComposition as $prop) {
    //     file_put_contents($write, $systemBody->solidComposition->$prop . ",", FILE_APPEND | LOCK_EX);
    // }
    // foreach ($atmosphereComposition as $prop) {
    //     file_put_contents($write, $systemBody->atmosphereComposition->$prop . ",", FILE_APPEND | LOCK_EX);
    // }
    // foreach ($starOnlyProperties as $prop) {
    //   file_put_contents($write, $systemBody->$prop . ",", FILE_APPEND | LOCK_EX);
    // }
    // foreach ($materials as $prop) {
    //   file_put_contents($write, $systemBody->materials->$prop . ",", FILE_APPEND | LOCK_EX);
    // }
    // file_put_contents($write, (property_exists($systemBody,'rings')?'1':'') . ",", FILE_APPEND | LOCK_EX);
    // file_put_contents($write, "\r\n", FILE_APPEND | LOCK_EX);

    // var_dump($systemBody->rings);
    if (property_exists($systemBody,'rings')) {
      foreach ($systemBody->rings as $ring) {
        foreach ($ringProperties as $prop) {
            file_put_contents($write, $ring->$prop . ",", FILE_APPEND | LOCK_EX);
        }
        file_put_contents($write, "\r\n", FILE_APPEND | LOCK_EX);
      }
    }

    // var_dump($systemBody->belts);
    if (property_exists($systemBody,'belts')) {
      foreach ($systemBody->belts as $belt) {
        foreach ($ringProperties as $prop) {
            file_put_contents($write, $belt->$prop . ",", FILE_APPEND | LOCK_EX);
        }
        file_put_contents($write, "\r\n", FILE_APPEND | LOCK_EX);
      }
    }
  }

}
