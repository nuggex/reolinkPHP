<?php

require_once "rootdir.php";
require_once ROOT_DIR . "/reolink.class.php";

$reoLink = new reoLink();

$id = $argv[1];
try {
    print_r($reoLink->PtzPresetControl($id));
} catch (\GuzzleHttp\Exception\GuzzleException $e) {
    print_r("ERROR \n");
    print_r($e->getCode());
    print_r($e->getMessage());
}
