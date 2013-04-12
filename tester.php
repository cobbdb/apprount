<?php

/**
 * Unit tests for Apprount API.
 */

require_once "./private/API.php";
require_once "./private/Console.php";


Console::log("***************************************", 3);
Console::log("** Testing API::getTitleInfo...");
try {
    $results = API::getTitleInfo(Array("coolpage", "energy", "category:energy"));
    if (sizeof($results) != 3) {
        throw new Exception("TEST 1 :: Wrong number of results.");
    }
    if (sizeof($results["7754"]) != 7) {
        throw new Exception("TEST 2 :: Wrong number of healthy page result information.");
    }
    if (sizeof($results["7035"]) != 7) {
        throw new Exception("TEST 3 :: Wrong number of healthy category result information.");
    }
    Console::log(".. Passed!", 2, 2);
} catch (Exception $err) {
    Console::log(".. Failed! " . $err->getMessage(), 2, 2);
}


Console::log("***************************************", 3);
Console::log("** Testing API::newCategoryReport...");
try {
    $results = API::newCategoryReport("category:energy");
    if ($results->getSubcategoryCount() < 10) {
        throw new Exception("TEST 1 :: Wrong number of Subcategories.");
    }
    if ($results->getSubpageCount() < 10) {
        throw new Exception("TEST 2 :: Wrong number of Subpages.");
    }
    Console::log(".. Passed!", 2, 2);
} catch (Exception $err) {
    Console::log(".. Failed! " . $err->getMessage(), 2, 2);
}


Console::log("***************************************", 3);
Console::log("** Testing API::newReport...");
try {
    $results = API::newReport(1, "coolpage|energy|category:energy");
    if ($results->getSubpageCnt() != 3) {
        throw new Exception("TEST 1 :: Wrong number of total pages.");
    }
    if ($results->getAllViews() < 1000) {
        throw new Exception("TEST 2 :: Wrong number of total page views.");
    }
    Console::log(".. Passed!", 2, 2);
} catch (Exception $err) {
    Console::log(".. Failed! " . $err->getMessage(), 2, 2);
}

















