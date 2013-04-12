<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/util/Engine.php";
require_once "Catalog.php";
require_once "Console.php";

/**
 * Page.php
 * 
 * @author Dan Cobb
 * @since 1.4.0
 * @date October 5, 2012
 */
abstract class Page {
    /**
     * Check if a title begins with the category prefix. Category pages that have
     * already been accounted for will be treated as Content Pages unless using
     * strict rules.
     * @param {String} title
     * @param {Boolean} [strict] Use strict rules for Category checking.
     * @returns {Boolean}
     */
    public static function isCategory($title, $strict = false) {
        $patternMatch = preg_match("/^(Category:)/i", $title);
        $catalogMatch = Catalog::hasPage($title);
        if ($strict and $patternMatch) {
            return true;
        } else if ($patternMatch and !$catalogMatch) {
            Console::log("$title is a Category!");
            return true;
        }
        return false;
    }
    
    /**
     * @returns {Boolean} True if page is not broken.
     */
    public static function isHealthy($page) {
        return isset($page["counter"]);
    }
    
    
    protected $title, $views, $depth, $uri, $link;
    protected $subpages = Array();
    protected $subcats = Array();
    
    public abstract function toHTML();
    public abstract function toWiki($depth);
    public abstract function toCSV($title);
    
    public function __construct($title, $views, $depth) {
        $this->title = $title;
        $this->views = $views;
        $this->depth = $depth;
        $this->uri = "appropedia.org/" . $title;
        
        // Add page information to the global catalog.
        Catalog::addPage($title, $views);
        
        // Some titles contain slashes, so encode only each subpath of the title.
        $pathSections = explode("/", $title);
        $encodedSections = Array();
        foreach ($pathSections as $subpath) {
            $encodedSections[] = urlencode($subpath);
        }
        $this->link = "http://www.appropedia.org/" . implode("/", $encodedSections);
    }
    
    public function getPages() {
        return array_merge($this->subpages, $this->subcats);
    }
    
    public function getPageSet() {
        $set = Array(
            $this->title => $this->views
        );
        
        $pages = $this->getPages();
        foreach ($pages as $page) {
            $set += $page->getPageSet();
        }
        return $set;
    }
    
    public function getTitle() {
        return $this->title;
    }
    
    public function getSubpageCnt() {
        return sizeof($this->getPageSet()) - 1;
    }
    
    public function getSubcatCnt() {
        $count = sizeof($this->subcats);
        foreach ($this->subcats as $page) {
            $count += $page->getSubcatCnt();
        }
        return $count;
    }
    
    public function getAllViews() {
        $total = 0;
        $set = $this->getPageSet();
        foreach ($set as $count) {
            $total += $count;
        }
        return $total;
    }
    
    public function addPage($page) {
        if (Page::isCategory($page->getTitle(), true)) {
            $this->subcats[] = $page;
        } else {
            $this->subpages[] = $page;
        }
    }
}



class Report extends Page {
    public function __construct($depth) {
        parent::__construct("REPORT", 0, $depth);
    }
    
    public function toHTML() {
        return Template::render(
            "./private/views/pages/report.html.view",
            $this->model()
        );
    }
    
    public function toWiki($arg) {
        return Template::render(
            "./private/views/pages/report.wiki.view",
            $this->model()
        );
    }
    
    public function toCSV($arg) {
        return Template::render(
            "./private/views/pages/report.csv.view",
            $this->model()
        );
    }
    
    private function model() {
        return Array(
            "searchDepth" => $this->depth,
            "totalViews" => $this->getAllViews(),
            "totalPages" => $this->getSubpageCnt(),
            "pages" => $this->getPages()
        );
    }
}



class ContentPage extends Page {
    public function toHTML() {
        return Template::render("./private/views/pages/contentpage.html.view", Array(
            "indent" => $this->depth * 22,
            "title" => $this->title,
            "views" => $this->views,
            "link" => $this->link,
            "uri" => $this->uri
        ));
    }
    
    public function toWiki($searchDepth) {
        return Template::render("./private/views/pages/contentpage.wiki.view", Array(
            "title" => $this->title,
            "views" => $this->views,
            "depth" => $this->depth,
            "searchDepth" => $searchDepth
        ));
    }
    
    public function toCSV($parentTitle) {
        return Template::render("./private/views/pages/contentpage.csv.view", Array(
            "title" => $this->title,
            "views" => $this->views,
            "uri" => $this->uri,
            "parentTitle" => $parentTitle
        ));
    }
}



/**
 * Broken pages are those with incorrect query titles.
 * These hold no view or uri information.
 */
class BrokenPage extends Page {
    public function __construct($title, $depth) {
        parent::__construct($title, 0, $depth);
    }
    
    public function toHTML() {
        return Template::render("./private/views/pages/brokenpage.html.view", Array(
            "indent" => $this->depth * 22,
            "alt" => "broken page",
            "title" => $this->title,
            "msg" => "Page Not Found"
        ));
    }
    
    public function toWiki($searchDepth) {
        return Template::render("./private/views/pages/brokenpage.wiki.view", Array(
            "depth" => $this->depth,
            "searchDepth" => $searchDepth,
            "msg" => "Page Not Found: " . $this->title
        ));
    }
    
    public function toCSV($parentTitle) {
        return Template::render("./private/views/pages/brokenpage.csv.view", Array(
            "msg" => "Page Not Found: " . $this->title,
            "parentTitle" => $parentTitle
        ));
    }
}



/**
 * CategoryPage is a child of Page that contains a
 * collection of subpages.
 */
class CategoryPage extends Page {
    /** Next available category id. */
    private static $maxCatID = 0;
    /** Unique page id. */
    private $catID;
    
    public function __construct($title, $views, $depth) {
        parent::__construct($title, $views, $depth);
        $this->catID = self::$maxCatID;
        self::$maxCatID += 1;
    }
    
    public function toHTML() {
        return Template::render("./private/views/pages/categorypage.html.view", Array(
            "indent" => $this->depth * 22,
            "catID" => $this->catID,
            "title" => $this->title,
            "link" => $this->link,
            "uri" => $this->uri,
            "views" => $this->views,
            "totalViews" => $this->getAllViews(),
            "cntSubpagesDirect" => sizeof($this->getPages()),
            "cntSubpagesTotal" => $this->getSubpageCnt(),
            "cntSubcategoriesDirect" => sizeof($this->subcats),
            "cntSubcategoriesTotal" => $this->getSubcatCnt(),
            "subpages" => $this->getPages()
        ));
    }
    
    public function toWiki($searchDepth) {
        return Template::render("./private/views/pages/categorypage.wiki.view", Array(
            "title" => $this->title,
            "views" => $this->views,
            "depth" => $this->depth,
            "searchDepth" => $searchDepth,
            "totalViews" => $this->getAllViews(),
            "cntSubpagesDirect" => sizeof($this->getPages()),
            "cntSubpagesTotal" => $this->getSubpageCnt(),
            "cntSubcategoriesDirect" => sizeof($this->subcats),
            "cntSubcategoriesTotal" => $this->getSubcatCnt(),
            "subpages" => $this->getPages()
        ));
    }
    
    public function toCSV($parentTitle) {
        return Template::render("./private/views/pages/categorypage.csv.view", Array(
            "title" => $this->title,
            "views" => $this->views,
            "totalViews" => $this->getAllViews(),
            "uri" => $this->uri,
            "cntSubpagesDirect" => sizeof($this->getPages()),
            "cntSubpagesTotal" => $this->getSubpageCnt(),
            "cntSubcategoriesDirect" => sizeof($this->subcats),
            "cntSubcategoriesTotal" => $this->getSubcatCnt(),
            "subpages" => $this->getPages(),
            "parentTitle" => $parentTitle
        ));
    }
}



/**
 * Leaf category pages are those at the edge of the
 * search depth and, therefore, have no subpages.
 */
class LeafCategory extends Page {
    public function toHTML() {
        return Template::render("./private/views/pages/leafcategory.html.view", Array(
            "indent" => $this->depth * 22,
            "title" => $this->title,
            "views" => $this->views,
            "totalViews" => $this->getAllViews(),
            "cntSubpagesDirect" => sizeof($this->getPages()),
            "cntSubpagesTotal" => $this->getSubpageCnt(),
            "cntSubcategoriesDirect" => sizeof($this->subcats),
            "cntSubcategoriesTotal" => $this->getSubcatCnt(),
            "link" => $this->link,
            "uri" => $this->uri
        ));
    }
    
    public function toWiki($searchDepth) {
        return Template::render("./private/views/pages/categorypage.wiki.view", Array(
            "title" => $this->title,
            "views" => $this->views,
            "depth" => $this->depth,
            "searchDepth" => $searchDepth,
            "cntSubpagesDirect" => sizeof($this->getPages()),
            "cntSubpagesTotal" => $this->getSubpageCnt(),
            "cntSubcategoriesDirect" => sizeof($this->subcats),
            "cntSubcategoriesTotal" => $this->getSubcatCnt(),
            "totalViews" => $this->getAllViews()
        ));
    }
    
    public function toCSV($parentTitle) {
        return Template::render("./private/views/pages/categorypage.csv.view", Array(
            "title" => $this->title,
            "views" => $this->views,
            "totalViews" => $this->getAllViews(),
            "uri" => $this->uri,
            "cntSubpagesDirect" => sizeof($this->getPages()),
            "cntSubpagesTotal" => $this->getSubpageCnt(),
            "cntSubcategoriesDirect" => sizeof($this->subcats),
            "cntSubcategoriesTotal" => $this->getSubcatCnt(),
            "parentTitle" => $parentTitle
        ));
    }
}
