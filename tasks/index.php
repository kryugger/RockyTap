<?php

ini_set("log_errors", "off");
error_reporting(0);

$request_uri = $_SERVER['REQUEST_URI'];

$new_url = 'https://' . $_SERVER['HTTP_HOST'];

header('Location: ' . $new_url, true, 301);
exit();