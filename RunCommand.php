<?php

/**
 *
 * Example of Run Command
 */
require_once "rootdir.php";
require_once "reolink.class.php";

$reoLink = new reoLink();

// ReturnType can be
// statusCode
// body
// null

$returnType = "statusCode";
$command = "GetAbility";
$object = array(
    "User" => array(
        "userName" => "admin"
    )
);

try {
    print_r($reoLink->RunCommand($command, $object, $returnType));
} catch (\GuzzleHttp\Exception\GuzzleException $e) {
    print_r("ERROR \n");
    print_r($e->getCode());
    print_r($e->getMessage());
}
