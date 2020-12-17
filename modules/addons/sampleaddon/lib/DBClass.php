<?php

/**
 * Short description for file
 *
 * PHP version 7.4
 *
 * LICENSE: This source file is free to use, modify or share.
 * The source code is available through the world-wide-web at the following URI:
 * https://github.com/satyabeer/whmcs-dbclass.git.
 *
 * @category   WHMCS
 * @package    Modules
 * @author     Original Author <satyabeeryadav28@gmail.com>
 * @version    Release: 1.0
 * @link       https://github.com/satyabeer/whmcs-dbclass.git
 */

/**
 * Change the namespace with your moduletype and modulename
 * Example: namespace WHMCS\Module\Addons\SampleAddon; to namespace WHMCS\Module\{ModuleType}\{ModuleName};
*/

namespace WHMCS\Module\Addons\SampleAddon;

use WHMCS\Database\Capsule;

final class DBClass
{
    /**
     * Check the table existence
     *
     * @return boolean
     */
    public static function hasTable($table)
    {
        return Capsule::schema()->hasTable($table);
    }

    /**
     * Create the tables
     *
     * @return void
     */
    public static function createTables($tables)
    {
        try {
            foreach($tables as $table => $structure) {
                // check table existence
                if( !self::hasTable($table) ) {
                    // create table structure
                    Capsule::schema()->create($table, function ($t) use($structure) {
                        foreach($structure as $column => $type) {
                            $t->$type($column);
                        }
                    });
                }
            }
        } catch (\Throwable $e) {
            logActivity("Unable to create Table: {$e->getMessage()}");
        }
    }

    /**
     * Drop the tables
     *
     * @return void
     */
    public static function dropTable($tables)
    {
        try {
            if (is_array($tables)) {
                foreach($tables as $table) {
                    // drop table
                    Capsule::schema()->dropIfExists($table);
                }
            } else {
                // drop table
                Capsule::schema()->dropIfExists($tables);
            }
        } catch (\Throwable $e) {
            logActivity("Unable to create Table: {$e->getMessage()}");
        }
    }

    /**
     * Insert row in table
     *
     * @return boolean
     */
    public static function insertRecord($table, $data)
    {
        try {
           return Capsule::table($table)->insert($data);
        } catch (Exception $ex) {
            logActivity("Can not insert data into {$table}, error: {$ex->getMessage()}");
        }
    }

    /**
     * Insert row in table and return inserted id value
     *
     * @return int
     */
    public static function insertGetId($table, $data)
    {
        try {
           return Capsule::table($table)->insertGetId($data);
        } catch (Exception $ex) {
            logActivity("Can not insert data into {$table}, error: {$ex->getMessage()}");
        }
    }

    /**
     * Update the table records
     * return value will be the number of rows updated
     *
     * @return int
     */
    public static function updateRecord($table, $data, $where = null)
    {
        try {
            if (is_null($where))
                return Capsule::table($table)->update($data);
            else
                return Capsule::table($table)->where($where)->update($data);
        } catch (Exception $ex) {
            logActivity("Can not update record into {$table}, error: {$ex->getMessage()}");
        }
    }

    /**
     * delete the rows from table
     *
     * @return int
     */
    public function deleteRecord($table, $where = null)
    {
        try {
            if (is_null($where))
                return Capsule::table($table)->delete();
            else
                return Capsule::table($table)->where($where)->delete();
        } catch (Exception $ex) {
            logActivity("Can not delete record from {$table}, error: {$ex->getMessage()}");
        }
    }

    /**
     * Get the single row of the table
     *
     * @return object
     */
    public static function getRow($table, $where)
    {
        try {
            return Capsule::table($table)->where($where)->first();
        } catch (Exception $ex) {
            logActivity("Can not get data from {$table}, error: {$ex->getMessage()}");
        }
    }

    /**
     * Get the multiple rows of the table
     *
     * @return object
     */
    public static function getResult($table, $where = null)
    {
        try {
            if (is_null($where))
                return Capsule::table($table)->get();
            else
                return Capsule::table($table)->where($where)->get();
        } catch (Exception $ex) {
            logActivity("Can not get data from {$table}, error: {$ex->getMessage()}");
        }
    }
}