#!/usr/bin/env php
<?php

//$loader = require __DIR__ . '/vendor/autoload.php';

$records = cleanedData(
    json_decode(
        file_get_contents(
            __DIR__ . '/documents.json'
        ),
        true
    )
);

$html = "<html><body><h1>List of 'transport' defendants</h1><ul>";
foreach ($records as $record) {
    if (strpos(strtolower($record['defendant']), 'transport') !== false) {
        $html .= '<li>'.$record['title'].'</li>';
    }
}
$html .= "</ul></body></html>";
echo $html;

/******************************************************************************/

function cleanedData(array $documents)
{
    $records = [];
    foreach ($documents as $document) {
        $record = [
            'title' => $document['document']['title'],
            'claimant' => getClaimant($document['document']['title']),
            'defendant' => getDefendant($document['document']['title']),
        ];

        $records[] = $record;
    }

    return $records;
}

function getClaimant($string) {

    $string = cleanString($string);

    if (substr_count($string, ':')) {
        $stringWithNoId = trim(explode(':', $string)[0]);
    } else {
        $stringWithNoId = $string;
    }
    $parties = explode(' v ', $stringWithNoId);

    return trim($parties[0]);
}

function getDefendant($string) {
//    if (strpos($string, "The Governing Body of Barley Mow Primary School") !== false) {
//        echo "hello";
//    }

    $string = cleanString($string);

    if (substr_count($string, ':')) {
        $stringWithNoId = trim(explode(':', $string)[0]);
    } else {
        $stringWithNoId = $string;
    }
    $parties = explode(' v ', $stringWithNoId);

    if (count($parties) == 1) {
        var_dump($string);
        die;
    }

    return trim($parties[1]);
}

function getId($string)
{
    if (substr_count($string, ':')) {
        var_dump($string);
        return trim(explode(':', $string)[1]);
    }
}

function cleanString($string) {
    if ($string == "Mr J Stevens and others\tJaguar Land Rover Ltd and others\t3347577/2016 and others") {
        $string = 'Mr J Stevens and others   v  Jaguar Land Rover Ltd and others : 3347577/2016 and others';
    }
    if ($string == "Ms J Barkerv The Governing Body of Barley Mow Primary School and other:2500047/2017	") {
        $string = 'Ms J Barker v The Governing Body of Barley Mow Primary School and other:2500047/2017';
    }
    if ($string == "Ms Z Ayb - Birmingham City Council: 1306418/2012") {
        $string = 'Ms Z Ayb v Birmingham City Council: 1306418/2012';
    }

    $string = str_replace("\t", ' ', $string);
    $string = str_replace(' V ', ' v ', $string);
    $string = str_replace('-v-', ' v ', $string);

    return $string;
}
