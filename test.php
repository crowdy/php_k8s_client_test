<?php
require __DIR__ . '/vendor/autoload.php';

use Maclof\Kubernetes\Client;
use GuzzleHttp\Client as GuzzleClient;

$client = new Client([
    'master' => 'https://172.29.8.65:6443/',
]);
$client->setOptionsFromKubeconfigFile('kube_config.yml');

// Find pods by label selector
$pods = $client->pods()->setLabelSelector([
    'name'    => 'test',
    'version' => 'a',
])->find();

// Both setLabelSelector and setFieldSelector can take an optional
// second parameter which lets you define inequality based selectors (ie using the != operator)
$pods = $client->pods()->find();
$pods = $client->pods()->setLabelSelector([
    'name'    => 'test'],
    ['env'     =>  'staging']
)->find();

// Find pods by field selector
$pods = $client->pods()->setFieldSelector([
    'metadata.name' => 'test',
])->find();

// Find first pod with label selector (same for field selector)
$pod = $client->pods()->setLabelSelector([
    'name' => 'test',
])->first();
