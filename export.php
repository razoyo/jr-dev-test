<?php

/**
 * @author William Byrne <wbyrne@razoyo.com>
 * Documentation: https://github.com/razoyo/devtest
 */

require_once __DIR__ . '/raz-lib.php';
require_once __DIR__ . '/dev-lib.php';

$apiWsdl = 'https://www.shopjuniorgolf.com/api/?wsdl';
$apiUser = 'devtest';
$apiKey = getenv('RAZOYO_TEST_KEY');

$formatKey = 'csv'; // csv, xml, or json

// Connect to SOAP API using PHP's SoapClient class
// Feel free to create your own classes to organize code
$soap = new SoapClient($apiWsdl);
// ...

// You will need to create a FormatFactory.
$factory = new FormatFactory(); 
$format = $factory->create($formatKey);

// See ProductOutput in raz-lib.php for reference
$output = new ProductOutput();
// ...
$output->format();
