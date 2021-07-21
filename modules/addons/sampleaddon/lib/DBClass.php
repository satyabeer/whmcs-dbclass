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

namespace WHMCS\Module\Addon\SampleAddon;

use WHMCS\Database\Capsule;

final class DBClass
{
    /**
     * Check the table existence
     *
     * @return boolean
     */
    public static function hasTable(string $table)
    {
        return Capsule::schema()->hasTable($table);
    }

    /**
     * Create the tables
     *
     * @return void
     */

    public static function createTables(array $tables)
    {
        try {
            foreach($tables as $table => $structure) {
                // check table existence
                if( !self::hasTable($table) ) {
                    // create table structure
                    Capsule::schema()->create($table, function ($t) use($structure) {
                        foreach($structure as $column => $type) {

                            $types = explode(",",$type);
                            $dataType = $types[0];

                            if (count($types) > 1) {
                                if ($types[1] == 'nullable') {
                                    (count($types) === 5) ? $t->$dataType($column)->nullable($types[2])->default(null) : $t->$dataType($column)->nullable($types[2]);
                                } elseif($types[1] == 'currenttimestamp') {
                                    $t->$dataType($column)->default(Capsule::raw("CURRENT_TIMESTAMP"));
                                } elseif($types[1] == 'onupdatecurrenttimestamp') {
                                    $t->$dataType($column)->default(Capsule::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
                                } elseif (count($types) == 3) {
                                    $enumValues = explode("|", $types[1]);
                                    $defaultValue = str_replace('default:', '',$types[2]);
                                    $t->$dataType($column, $enumValues)->default($defaultValue);
                                }
                            } else {
                                $t->$dataType($column);
                            }
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
    public static function dropTable(mixed $tables)
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
    public static function insertRecord(string $table, array $data)
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
    public static function insertGetId(string $table, array $data)
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
    public static function updateRecord(string $table, array $data, array $where = null)
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
    public function deleteRecord(string $table, array $where = null)
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
    public static function getRow(string $table, array $where, string $column = null, string $order = null)
    {
        try {
            if (!empty($column) && !empty($order) && in_array($order, ['ASC', 'asc', 'DESC', 'desc'])) {
                return Capsule::table($table)->where($where)->orderBy($column, $order)->first();
            }

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
    public static function getResult(string $table, array $where = null, string $column = null, string $order = null)
    {
        try {
            if (is_null($where)) {

                if (!empty($column) && !empty($order) && in_array($order, ['ASC', 'asc', 'DESC', 'desc'])) {
                    return Capsule::table($table)->orderBy($column, $order)->get();
                }

                return Capsule::table($table)->get();
            }
            else {

                if (!empty($column) && !empty($order) && in_array($order, ['ASC', 'asc', 'DESC', 'desc'])) {
                    return Capsule::table($table)->where($where)->orderBy($column, $order)->get();
                }

                return Capsule::table($table)->where($where)->get();
            }
        } catch (Exception $ex) {
            logActivity("Can not get data from {$table}, error: {$ex->getMessage()}");
        }
    }
}
