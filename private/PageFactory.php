<?php

require_once "Page.php";
require_once "API.php";

/**
 * PageFactory.php
 * 
 * @author Dan Cobb
 * @since 1.4.0
 * @date October 4, 2012
 */
class PageFactory {
    private static $searchDepth;
    
    /**
     * @param [searchDepth] Required on first call.
     * @returns Instance of an appropriate Page subclass.
     */
    public static function newPage($page, $pageDepth, $depth = null) {
        // Set search depth on first call.
        if (isset($depth)) {
            self::$searchDepth = $depth;
        }
        
        if (Page::isCategory($page["title"])) {
            $temp = self::newCategory($page, $pageDepth);
            return $temp;
        }
        return self::newLeaf($page, $pageDepth);
    }
    
    
    /**
     * Creates one of: ContentPage, BrokenPage
     */
    private static function newLeaf($page, $pageDepth) {
        if (Page::isHealthy($page)) {
            return new ContentPage($page["title"], $page["counter"], $pageDepth);
        }
        return new BrokenPage($page["title"], $pageDepth);
    }
    
    
    
    /**
     * Creates one of: CategoryPage, LeafCategory, BrokenCategory
     */
    private static function newCategory($page, $pageDepth) {
        if (Page::isHealthy($page)) {
            // healthy categories have two types: node and leaf
            if ($pageDepth < self::$searchDepth) {
                return self::newCategoryPage($page["title"], $page["counter"], $pageDepth);
            }
            return self::newLeafCategory($page["title"], $page["counter"], $pageDepth);
        }
        return new BrokenPage($page["title"], $pageDepth);
    }
    
    private static function newCategoryPage($title, $views, $depth) {
        $categoryReport = API::newCategoryReport($title);
        $info = API::getTitleInfo($categoryReport->getSubpageTitles());
        $cat = new CategoryPage($title, $views, $depth);
        
        foreach ($info as $page) {
            // recurse to create the new page
            $newPage = self::newPage($page, $depth + 1);
            $cat->addPage($newPage);
        }
        return $cat;
    }
    
    private static function newLeafCategory($title, $views, $depth) {
        $categoryReport = API::newCategoryReport($title);
        $info = API::getTitleInfo($categoryReport->getSubpageTitles());
        $cat = new LeafCategory($title, $views, $depth);
        
        foreach ($info as $page) {
            // recurse to create the new page
            $newPage = self::newPage($page, $depth + 1);
            $cat->addPage($newPage);
        }
        return $cat;
    }
}
