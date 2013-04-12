<?php

/**
 * index.php
 * Page routing for Apprount.
 * 
 * Query format in EBNF:
 * pequals.com/apprount/?[depth=<depth>&]titles=<page title>[|<page title>]
 * 
 * @author Dan Cobb
 * @since 1.4.0
 * @date October 5, 2012
 */

require_once "./private/API.php";
require_once "./private/Console.php";
require_once "../util/Engine.php";


define("SECRET", "RobinHood123");
//Console::unlock();
session_start();

$rTitles = @$_REQUEST["titles"];
$rDepth = @$_REQUEST["depth"];

// Ensure state.
if (isset($rTitles) and strlen($rTitles) > 0) {
    // Any request with a titles param -> search.
    $action = "search";
    $_SESSION["titles"] = $rTitles;
} else if (isset($_SESSION["titles"]) and isset($_REQUEST["key"])) {
    // Verify search key.
    if ($_REQUEST["key"] == $_SESSION["key"]) {
        // No titles param and titles set in session -> report.
        $action = "report";
    } else {
        // Search key was invalid -> abort.
        exit;
    }
} else {
    // With no titles param and no titles in session -> index.
    $action = "index";
}


// Ensure depth parameter.
if ($action == "search") {
    if (isset($_REQUEST["depth"])) {
        // Correct for full search depth.
        if ($rDepth == "full") {
            $_SESSION["depth"] = 100;
        } else {
            // Search with a depth set -> use as requested.
            $_SESSION["depth"] = $rDepth;
        }
    } else {
        // Search with no depth set -> use default.
        $_SESSION["depth"] = 0;
    }
} else if ($action == "report") {
    if (!isset($_SESSION["depth"])) {
        // Report with no depth set -> use default.
        $_SESSION["depth"] = 0;
    }
}


// Prepare header content.
$head = Template::render("./private/views/head.view");

switch ($action) {
    // Search-in-Progress page.
    case "search":
        Console::clear();
        Console::log("Received request for $action.", 1, 1);
        
        // Salt the hash and store the key.
        $key = sha1(SECRET . rand());
        $_SESSION["key"] = $key;
        Console::log("Key is: $key");
        
        $body = Template::render("./private/views/search.view", Array(
            "key" => $key
        ));
        echo Template::render(null, Array(
            "head" => $head,
            "body" => $body
        ));
        break;
    
    
    // Conduct the actual search and respond.
    case "report":
        // Expand limit on execution time.
        ini_set('max_execution_time', 300);
        Console::log("Received request for $action.", 1, 1);
        
        // Calculate page view information.
        try {
            $report = API::newReport($_SESSION["depth"], $_SESSION["titles"]);
            session_destroy();
            
            $page = Template::render("./private/views/report.view", Array(
                "results" => $report->toHTML()
            ));
            
            echo json_encode(Array(
                "html" => $page,
                "wiki" => $report->toWiki(0),
                "csv" => $report->toCSV(0)
            ));
        } catch(Exception $exc) {
            Console::error($exc->getMessage());
            throw $exc;
        }
        break;
    
    
    // Show index page.
    default:
        Console::clear();
        Console::log("Received request for $action.", 1, 1);
        $body = Template::render("./private/views/index.view");
        echo Template::render(null, Array(
            "head" => $head,
            "body" => $body
        ));
        break;
}

// Log request end.
Console::log("Completed request for $action.", 0, 2);
Console::close();
