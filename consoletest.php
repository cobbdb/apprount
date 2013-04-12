<?php

require_once "./private/API.php";
require_once "./private/Console.php";

Console::clear();
Console::log("Message 1");
Console::log("Message 2");
Console::error("Error 1");
Console::log("Message 3");
Console::open();
Console::log("Message 4");
Console::open();
Console::log("Message 5");

var_dump(Console::toString());
