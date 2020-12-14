<?php
/*
 * Change the namespace with your moduletype and modulename
 * Example: namespace WHMCS\Module\Addons\SampleAddon; to namespace WHMCS\Module\{ModuleType}\{ModuleName};
*/

namespace WHMCS\Module\Addons\SampleAddon;

use WHMCS\Database\Capsule;

final class DBClass {

    public static function createSchema($schemas) {
        try {
            foreach($schemas as $table => $structure) {
                // check table existence
                if( !Capsule::schema()->hasTable($table) ) {
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

    public static function getRow($table, $where) {
        $query = Capsule::table($table);
        $clause = '';
        // handle between clause
        if(array_key_exists('whereBetween', $where)) {
            $clause .= self::recursiveClause($where['between']);
        }
        // handle notbetween clause
        if(array_key_exists('whereNotBetween', $where)) {
            $clause .= self::recursiveClause($where['whereNotBetween']);
        }

        $query = $query->$clause;

        echo $query;die;

        return Capsule::table($table)->where($where)->first();
    }

    public static function getResult($table, $where = null) {
        if(is_null($where)) {
            return Capsule::table($table)->get();
        } else {
            return Capsule::table($table)->where($where)->get();
        }
    }

    public static function insertData($table, $data) {
        try {
            $insertId = Capsule::table($table)->insert($data);
        } catch (Exception $ex) {
            logActivity("Can not insert data into {$table}, error: {$ex->getMessage()}");
        }

        return $insertId;
    }

    public static function updateData($table, $data, $where = null) {
        if(is_null($where)) {
            return Capsule::table($table)->update($data);
        } else {
            return Capsule::table($table)->where($where)->update($data);
        }
    }

    private function recursiveClause($clauseName) {
        $clause = '';
        foreach($clauseName as $column => $values) {
            $clause .= array_keys($clauseName)($column, $values);
        }

        return $clause;
    }
}
