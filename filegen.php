<?php

$type = $_REQUEST["type"];

if ($type == "wiki") {
    $ext = "wiki";
} elseif ($type == "csv") {
    $ext = "csv";
} else {
    die();
}

header("Cache-Control: ");
header("Content-type: text/plain");
header("Content-Disposition: attachment; filename=\"export.$ext\"");

echo $_REQUEST["content"];
