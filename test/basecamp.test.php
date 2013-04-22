<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$basecampSetting = new BasecampSetting();

$bc = new Basecamp($basecampSetting->url, $basecampSetting->user, $basecampSetting->pass);
$response = $bc->getProjects();
// see the XML output
print_r($response);