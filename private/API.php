<?php

/**
 * Api.php
 *
 * @author Dan Cobb
 * @since 1.4.1
 */

require_once "eta.php";
require_once "Console.php";
require_once "Catalog.php";
require_once "Page.php";
require_once "PageFactory.php";

/**
 * Convenience class for category information.
 */
class CategoryReport {
    private $pageTitles;
    private $categoryCount = 0;

    /**
     * @param {Array} titles Collection of page titles.
     */
    public function __construct($titles) {
        $this->pageTitles = $titles;
        foreach ($this->pageTitles as $title) {
            if (Page::isCategory($title)) {
                $this->categoryCount += 1;
            }
        }
    }

    public function getSubpageTitles() {
        return $this->pageTitles;
    }

    public function getSubpageCount() {
        return sizeof($this->pageTitles);
    }

    public function getSubcategoryCount() {
        return $this->categoryCount;
    }
}


/**
 * Singleton containing collection of tools to handle Appropedia page operations.
 */
class API {
    /**
     * Cookie information to be added to request headers.
     */
    public static $cookieJar = Array();

    /**
     * True if API is currently logged in.
     */
    public static $loggedIn = false;

    /**
     * Parse an http response header for cookie information.
     * @see http://stackoverflow.com/questions/10958491/get-cookie-with-file-get-contents-in-php/10958820
     * @param {Array} responseHeader Magic value set by calling file_get_contents()
     * @returns {Array}
     */
    public static function getResponseCookies($responseHeader) {
        $cookies = Array();
        foreach ($responseHeader as $hdr) {
            if (preg_match('/^Set-Cookie:\s*([^;]+)/', $hdr, $matches)) {
                parse_str($matches[1], $tmp);
                $cookies += $tmp;
            }
        }
        return $cookies;
    }

    /**
     * Query MediaWiki API for information.
     * @param {Array} data Request parameters.
     * @param {Array} [header] Additional header information. Defaults to cookie jar.
     * @param {String} [url] API url. Defaults to Appropedia's API.
     * @returns {Array} API response.
     */
    public static function query($data, $header = null, $url = false) {
        // Assemble header from cookie jar if no header supplied.
        if ($header === null) {
            $header = Array();
            $header[] = self::buildCookieSheet(self::$cookieJar);
        }

        // Send request for data.
        $url = ($url) ? $url : "http://www.appropedia.org/api.php";
        $context = self::createPostContext($data, $header);
        $res = file_get_contents($url, false, $context);
        $res = unserialize($res);

        // Add any cookies from the response to the cookie jar.
        $cookies = self::getResponseCookies($http_response_header);
        self::$cookieJar = array_merge(self::$cookieJar, $cookies);

        // Build debug output.
        Console::log("Query for: " . implode($data, "|"), 1);
        Console::log("Response Content was: " . json_encode($res));

        return $res;
    }

    /**
     * Handshake with Appropedia and gather cookie information.
     * @throws Exception on login errors.
     */
    public static function login() {
        // Early exit if already logged into Appropedia.
        Console::log("Call to log in.");
        if (self::$loggedIn) {
            Console::error("Already logged in.");
            return;
        }
        Console::log("Starting login process.");

        // Initial request parameters.
        $data = Array(
            "format" => "php",
            "action" => "login",
            "lgname" => "Pequalsbot",
            "lgpassword" => file_get_contents("./private/login.txt")
        );

        // Query ignoring any existing cookie information.
        $res = self::query($data, Array());
        if (strcasecmp($res["login"]["result"], "NeedToken") != 0) {
            throw new Exception("Failed login request. Responded with result of " . $res["login"]["result"]);
        }

        $data["lgtoken"] = $res["login"]["token"];

        $res = self::query($data);
        if (strcasecmp($res["login"]["result"], "Success") != 0) {
            throw new Exception("Failed login handshake. Responded with result of " . $res["login"]["result"]);
        }

        self::$loggedIn = true;
        Console::log("Successfully logged in.");
    }

    /**
     * @param depth
     * @param searchString
     * @returns {Report} Search report.
     */
    public static function newReport($depth, $searchString) {
        $titles = explode("|", $searchString);
        $info = self::getTitleInfo($titles);

        $report = new Report($depth);
        foreach ($info as $page) {
            $report->addPage(PageFactory::newPage($page, 0, $depth));

        }
        return $report;
    }

    /**
     * Factory for category report.
     * @param categoryTitle
     * @returns {CategoryReport}
     */
    public static function newCategoryReport($categoryTitle) {
        $titleList = self::getSubtitles($categoryTitle);
        return new CategoryReport($titleList);
    }

    /**
     * Query MediaWiki for page information on a set of page titles.
     * @param {Array} allTitles Collection of page titles.
     * @returns {Array}
     */
    public static function getTitleInfo($allTitles) {
        $length = sizeof($allTitles);
        $results = Array();

        for ($i = 0; $i < $length; $i += $querySize) {
            // Only increments of 500.
            $querySize = min($length, 500);
            $titles = array_slice($allTitles, $i, $querySize);

            $results += self::queryTitleInfo($titles);
        }

        return $results;
    }

    /**
     * Build a list of page titles contained under a category.
     * @param {String} root Root page to search through.
     * @returns {Array} Page titles.
     * @example Sample query:
     * www.appropedia.org/api.php?format=json&action=query&redirects=true&list=categorymembers&cmprop=title&cmlimit=15&cmtitle=category:energy&cmcontinue=page|414c5445524e4154494e472043555252454e54|12059
     */
    private static function getSubtitles($root) {
        $data = Array(
            "format" => "php",
            "action" => "query",
            "redirects" => "true",
            "list" => "categorymembers",
            "cmprop" => "title",
            "cmlimit" => "500",
            "cmtitle" => $root,
            "cmcontinue" => ""
        );
        $titles = Array();

        do {
            $res = self::query($data);

            foreach ($res["query"]["categorymembers"] as $page) {
                $titles[] = $page["title"];
            }

            $continue = isset($res["query-continue"]);
            if ($continue) {
                $data["cmcontinue"] = $res["query-continue"]["categorymembers"]["cmcontinue"];
            }
        } while ($continue);

        return $titles;
    }

    /**
     * Query for page info on a set of titles.
     * @param {Array} titles Collection of page titles.
     * @returns {Array} Page information.
     * @example Sample query:
     * www.appropedia.org/api.php?format=json&action=query&prop=info&titles=energy|water
     */
    private static function queryTitleInfo($titles) {
        // Log into Appropedia if necessary.
        if (sizeof($titles) > 50) {
            self::login();
        }

        $titles = implode("|", $titles);
        $data = Array(
            "format" => "php",
            "action" => "query",
            "prop" => "info",
            "titles" => $titles
        );

        $res = self::query($data);
        return $res["query"]["pages"];
    }

    /**
     * @param {Array} data Request parameters.
     * @param {Array} [header] Additional header information.
     * @returns {StreamContext}
     */
    private static function createPostContext($data, $header = Array()) {
        $header[] = "Content-type: application/x-www-form-urlencoded";
        $header[] = "User-Agent: Apprount/1.4.0 (http://www.pequals.com/apprount; cobbdb@gmail.com)";
        $opts = Array(
            "http" => Array(
                "method" => "POST",
                "header" => $header,
                "content" => http_build_query($data)
            )
        );
        return stream_context_create($opts);
    }

    /**
     * Convert a set of key/value cookie data pairs to a single string.
     * @param {Array} cookies In ["<key>"] = "<value>" form.
     * @returns {String} In [n] = "Cookie: <key>=<value>; " form.
     */
    private static function buildCookieSheet($cookies) {
        $sheet = "Cookie: ";
        foreach ($cookies as $key => $value) {
            $sheet .= "$key=$value; ";
        }
        return $sheet;
    }
}
