<?php
require __DIR__ . '/vendor/autoload.php';

use Maclof\Kubernetes\Client;
use Maclof\Kubernetes\Models\Job;
use Maclof\Kubernetes\Models\Pod;
use Maclof\Kubernetes\Models\Service;
use Maclof\Kubernetes\RepositoryRegistry;


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

/** @var Maclof\Kubernetes\Collections\PodCollection $pods */
$pods = $client->pods()->find();



echo "\n\nlist pods" . PHP_EOL;
$client->pods()->find()->each(function (Pod $pod) {
    echo $pod->getMetadata('name') . ' / ' . $pod->getMetadata('label') . PHP_EOL;
});

// create pod

if (! $client->pods()->exists('tkim-test')) {
    echo "\n\npod does not exist. creating" . PHP_EOL;
    $client->pods()->create(new Pod([
        'metadata' => [
            'name' => 'tkim-test',
            'labels' => [
                'name' => 'tkim-test',
            ],
        ],
        'spec'     => [
            'containers' => [
                [
                    'name'  => 'test',
                    'image' => 'nginx',
                ],
            ],
        ],
    ]));
} else {

    echo "\n\npod exists. deleting" . PHP_EOL;
    $client->pods()->deleteByName('tkim-test');
}


// list services
echo "\n\nlist services" . PHP_EOL;
$client->services()->find()->each(function (Service $job) {
    echo $job->getMetadata('name') . PHP_EOL;
});

// list jobs
echo "\n\nlist jobs" . PHP_EOL;
$client->jobs()->find()->each(function (Job $job) {
    echo $job->getMetadata('name') . PHP_EOL;
});

$pods = $client->pods()->setLabelSelector([
    'name'    => 'tkim-test'],
    ['env'     =>  'staging']
)->find();

// Find pods by field selector
$pods = $client->pods()->setFieldSelector([
    'metadata.name' => 'tkim-test',
])->find();

// Find first pod with label selector (same for field selector)
$pod = $client->pods()->setLabelSelector([
    'name' => 'tkim-test',
])->first();
