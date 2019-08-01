<?php

require __DIR__.'/../vendor/autoload.php';

$client = \Moay\OpensensemapApiClient\OpensensemapApiClientFactory::create();
$senseBoxData = $client->getSenseBoxData('5a0c2cc89fd3c200111118f0');

var_dump($senseBoxData);
