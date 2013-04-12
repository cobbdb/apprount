<?php

/**
 * System output console to "system.log" file.
 * @author Dan Cobb
 * @since 1.4.0
 * @date February 25, 2013
 */
class Console {
    /**
     * File context to use in input/output operations.
     */
    private static $handle = null;
    
    /**
     * Filename of the system's log.
     */
    private static $filename = "system.log";
    
    /**
     * Global state of Console for easy activation/deactivation.
     */
    private static $locked = true;
    
    /**
     * Activate the Console.
     */
    public static function unlock() {
        self::$locked = false;
    }
    
    /**
     * Open log file for read and write.
     */
    public static function open($newLog = true) {
        if (self::$locked) {
            return;
        } else if (self::isLive() and $newLog) {
            self::log("Call to reopen log when Console is already initialized.", 1, 2);
            return;
        }
        
        self::$handle = fopen(self::$filename, "a+");
        self::log("Log opened.", 1, 2);
    }
    
    /**
     * Reset log file and open for read and write.
     */
    public static function clear() {
        if (self::$locked) {
            return;
        }
        $fatReset = self::isLive();
        self::$handle = fopen(self::$filename, "w+");
        
        if ($fatReset) {
            self::log("Reset log when Console is already initialized.", 1, 2);
        } else {
            self::log("Log Reset.", 1, 2);
        }
    }
    
    /**
     * @returns {Boolean} True if the Console's log file has already been opened for use.
     */
    public static function isLive() {
        return (self::$handle !== null);
    }
    
    /**
     * Close log file.
     * @returns {Boolean} True if file closed successfully.
     */
    public static function close() {
        if (self::$locked) {
            return;
        }
        self::log("Log closed", 1, 1);
        return fclose(self::$handle);
    }
    
    /**
     * Write a string to the system log.
     * @param {String} msg Message to log.
     * @param {Number} [pre] Number of lines to break before message.
     * @param {Number} [post] Number of lines to break after message.
     * @returns {Boolean} False if write has failed.
     */
    public static function log($msg, $pre = 0, $post = 1) {
        if (self::$locked) {
            return;
        } else if (!self::isLive()) {
            self::open();
        }
        
        // Build log entry.
        $time = localtime(time(), true);
        $entry = "[" . $time["tm_hour"];
        $entry .= ":" . $time["tm_min"];
        $entry .= ":" . $time["tm_sec"];
        $entry .= "] " . $msg;
        
        // Add line breaks.
        for ($i = 0; $i < $pre; $i += 1) {
            $entry = "\n" . $entry;
        }
        for ($i = 0; $i < $post; $i += 1) {
            $entry .= "\n";
        }
        
        // Write to the log file.
        return fwrite(self::$handle, $entry);
    }
    
    public static function error($msg, $pre = 0, $post = 1) {
        $msg = "{ERROR} " . $msg;
        self::log($msg, $pre, $post);
    }
    
    /**
     * Read contents of log file.
     * @returns {String}
     */
    public static function toString() {
        return @file_get_contents(self::$filename);
    }
}
