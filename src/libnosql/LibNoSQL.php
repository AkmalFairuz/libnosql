<?php

namespace libnosql;

class LibNoSQL
{
    /**
     * You can use LibNoSQL::setDatabase( dbname ) to set database without edit the code.
     */

    /**
     * File Extension .ns (nosql) default
     */
    const FILE_EXTENSION = ".ns";

    private static $path;

    /**
     * Set database
     * @param string $dbName
     */
    public static function setDatabaseDirectory(string $dbName) {
        self::$path = $dbName.DIRECTORY_SEPARATOR;
    }

    /**
     * Optional, create database directory if doesn't exists
     */
    public static function init() {
        @mkdir(self::getPath());
        @mkdir(self::getPath().DIRECTORY_SEPARATOR."tables");
    }

    /**
     * @param string $table
     * @return Table
     */
    public static function getTable(string $table) {
        return new Table($table);
    }

    public static function getPath() {
        return self::$path;
    }

    /**
     * @param string $path
     * @return int
     */
    public static function removeFile(string $path): int {
        unlink($path);
        return 1;
    }

    /**
     * @param string $dirPath
     * @return int
     */
    public static function removeDir(string $dirPath): int {
        $files = 1;
        if(basename($dirPath) == "." || basename($dirPath) == "..") {
            return 0;
        }
        foreach (scandir($dirPath) as $item) {
            if($item != "." || $item != "..") {
                if(is_dir($dirPath . DIRECTORY_SEPARATOR . $item)) {
                    $files += self::removeDir($dirPath . DIRECTORY_SEPARATOR . $item);
                }
                if(is_file($dirPath . DIRECTORY_SEPARATOR . $item)) {
                    $files += self::removeFile($dirPath . DIRECTORY_SEPARATOR . $item);
                }
            }

        }
        rmdir($dirPath);
        return $files;
    }
}