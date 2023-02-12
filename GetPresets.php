<?php

require_once "reolink.class.php";

$reoLink = new reoLink();

try {
    $presets = $reoLink->getPtzPreset();
    print_r($presets);
} catch (\GuzzleHttp\Exception\GuzzleException $e) {
    print_r("ERROR \n");
    print_r($e->getCode());
    print_r($e->getMessage());
}
