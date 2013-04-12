<?php

require_once "Console.php";

/**
 * Catalog.php
 * 
 * @author Dan Cobb
 * @since 1.4.0
 * @date October 5, 2012
 */
class Catalog {
    /**
     * Collection of page titles mapped against their views.
     */
    private static $catalog = Array();
    
    /**
     * Adds a page to the catalog.
     * @param {String} title
     * @param {Number} views
     */
    public static function addPage($title, $views) {
        self::$catalog[$title] = $views;
        Console::log("Catalog now contains: " . var_export(self::$catalog, true), 1, 1);
    }
    
    /**
     * @param {String} title
     * @returns {Boolean} True if catalog contains the given page.
     */
    public static function hasPage($title) {
        return isset(self::$catalog[$title]);
    }
    
    /**
     * @param {String} title
     * @return {Number} Recorded views for a given page.
     * @throws {Exception} Error if title is not found in catalog.
     */
    public static function getViews($title) {
        if (self::hasPage($title)) {
            return self::$catalog[$title];
        }
        throw new Exception("Catalog does not contain page: $title");
    }
}