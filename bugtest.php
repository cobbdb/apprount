<?php

require_once "./private/API.php";
require_once "./private/Console.php";


Console::log("***************************************", 3);
Console::log("** Testing API::newCategoryReport...");
try {
    $results = API::newCategoryReport("Category:Global_Health_Medical_Device_Compendium");
    Console::log(var_dump($results), 2, 2);
} catch (Exception $err) {
    Console::log(".. Failed! " . $err->getMessage(), 2, 2);
}


Console::query("Query on Category:Global_Health_Medical_Device_Compendium", Array(
    "format" => "php",
    "action" => "query",
    "prop" => "info",
    "titles" => "Category:Global_Health_Medical_Device_Compendium"
));