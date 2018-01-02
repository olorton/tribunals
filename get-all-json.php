#!/usr/bin/env php
<?php

$loader = require __DIR__ . '/vendor/autoload.php';

$documents = [];
$client = new \GuzzleHttp\Client();
$count = 1;
while(true) {
    $json = $client
        ->request(
            'GET',
            'https://www.gov.uk'.
            '/employment-tribunal-decisions.json?page=' . $count
        )
        ->getBody()->getContents();
    $result = json_decode($json, true);

    if (!isset($result['documents']) || count($result['documents']) < 1) {
        $json = json_encode($documents);
        file_put_contents(__DIR__ . '/documents.json', $json);
        echo "done all";

        exit(0);
    }

    $documents = array_merge($documents, $result['documents']);

    echo "Done $count\n";
    $count++;
}
