# whmcs-dbclass

## Summary

DBClass is a [final class](https://www.php.net/manual/en/language.oop5.final.php) which is used to interact with WHMCS database tables. Currently, it interacts with single table and handles only where clause.

#### This supports for following modules

* Addon
* Fraud
* Gateway
* Registrar
* Report
* Server
* Widget

### Getting Started
Clone the module locally:
```python
git clone https://github.com/satyabeer/whmcs-dbclass.git
```
Go to the clone directory ```cd whmcs-dbclass```

Rename the module directory to your desired module name:

```
#for addons module try this
mv modules/addons/sampleaddon/ modules/addon/youraddonname/
#for registrars module try this
mv modules/addons/sampleaddon/ modules/registrar/yourregistrarname/
#for servers module try this
mv modules/addons/sampleaddon/ modules/server/yourservername/
```
Open the DBCLass.php file from `modules/{ModuleType}/{ModuleName}/lib/` folder.

Rename line no. 24 `namespace WHMCS\Module\Addon\SampleAddon;` with `namespace WHMCS\Module\{ModuleType}\{ModuleName};`

After that, move the modules to your WHMCS modules.
```
mv modules /path/to/whmcs/modules/
```

To invoke the class methods, simply add the "use" statement in your module files:

```
use WHMCS\Module\{ModuleType}\{ModuleName}\DBClass;
```

## Usage

1. To check the existence of the table

```python
DBClass::hasTable($tableName);
```

2. To create a single table; `users` is the name of the table and the key act as column name and the value act as the datatype for the column.

```python
$table = [
    'users' => [
        'id'        =>  'increments',
        'name'      =>  'string',
        'email'     =>  'string',
        'password'  =>  'string',
        'phone'     =>  'bigInteger'
    ]
];

DBClass::createTables($table);
```

3. To create multiple tables

```python
$tables = [
    'users' => [
        'id'        =>  'increments',
        'name'      =>  'string',
        'email'     =>  'string',
        'password'  =>  'string',
        'phone'     =>  'bigInteger'
    ],
    'role' => [
        'id'        =>  'increments',
        'role'      =>  'string'
    ],
    'roleusers' => [
        'id'        =>  'increments',
        'roleid'    =>  'integer'
        'userid'    =>  'integer'
    ]
];

DBClass::createTables($tables);
```

4. To create tables with default values

```python
$tables = [
    'users' => [
        'id'            =>  'increments',
        'name'          =>  'string',
        'email'         =>  'string',
        'password'      =>  'string',
        'address1'      =>  'string',
        'address2'      =>  'string,nullable,true',
        'phone'         =>  'bigInteger',
        'status'        =>  'enum,Active|Inactive,default:Inactive',
        'created_at'    =>  'timestamp,currenttimestamp',
        'updated_at'    =>  'timestamp,onupdatecurrenttimestamp',
    ]
];

DBClass::createTables($tables);
```


5. To drop a single table

```python
DBClass::dropTable($tableName);
```

6. To drop multiple tables

```python
$tables = ['firstTable', 'secondTable'];
DBClass::dropTable($tables);
```

7. To insert a single record in a table

```python
$tableName = 'users';
$values = [
    'name'      =>  'John Doe',
    'email'     =>  'johndoe@example.com',
    'password'  =>  md5('123456'),
    'phone'     =>  '1234567890'
];

DBClass::insertRecord($tableName, $values);
```

8. To insert multiple records in a table

```python
$tableName = 'users';
$values = [
    [
        'name'      =>  'John Doe',
        'email'     =>  'johndoe@example.com',
        'password'  =>  md5('123456'),
        'phone'     =>  '1234567890'
    ],
    [
        'name'      =>  'Shane Watson',
        'email'     =>  'shanewatson@example.com',
        'password'  =>  md5('secret'),
        'phone'     =>  '9876543210'
    ]
];

DBClass::insertRecord($tableName, $values);
```

9. To insert a single record and get the inserted id of the table

```python
$tableName = 'users';
$values = [
    'name'      =>  'John Doe',
    'email'     =>  'johndoe@example.com',
    'password'  =>  md5('123456'),
    'phone'     =>  '1234567890'
];

DBClass::insertGetId($tableName, $values);
```

10. To update all records with same value

```python
$tableName = 'users';
$values = [
    'name'      =>  'John Doe',
    'email'     =>  'johndoe@example.com',
    'password'  =>  md5('123456'),
    'phone'     =>  '1234567890'
];

DBClass::updateRecord($tableName, $values);
```

11. To update the particular records

```python
$tableName = 'users';
$values = [
    'name'      =>  'John Doe',
    'email'     =>  'johndoe@example.com',
    'password'  =>  md5('secret'),
    'phone'     =>  '9876543218'
];
$where = [
    ['name', 'like', '%J%'],
    ['phone', '=', '1235467890']
];

DBClass::updateRecord($tableName, $values, $where);
```

12. To delete all records

```python
DBClass::deleteRecord($tableName);
```

13. To delete the particular records

```python
$tableName = 'users';
$where = [
    ['name', 'like', '%J%'],
    ['phone', '=', '1235467890']
];

DBClass::deleteRecord($tableName, $where);
```

14. To retrieve all data

```python
DBClass::getResult('users');
```

15. To retrieve all data with latest order

```python
DBClass::getResult('users', null, 'id', 'DESC');
```

16. To retrieve multiple rows with multiple where clause

```python
$where = [
    ['name', 'like', '%J%'],
    ['phone', '=', '1235467890']
];

$result = DBClass::getResult('users', $where);
```

17. To retrieve multiple rows with multiple where clause and also with order by clause

```python
$where = [
    ['name', 'like', '%J%'],
    ['phone', '=', '1235467890']
];

$result = DBClass::getResult('users', $where, 'name', 'DESC');
```

18. To retrieve one row with where clause

```python
$where = [
    ['id', '=', 10]
];

$result = DBClass::getRow('users', $where);
```

19. If we have duplicate records and we want the latest records 

```python
$where = [
    ['id', '=', 10]
];

$result = DBClass::getRow('users', $where, 'id', 'DESC');
```

## Note
`we can use the following operators with where clause`
```python
>    # greater than
>=   # greater than equal to
<    # less than
<=   # less than equal to
<>   # not equal to
like # like operator
```
When we need to retrieve records with whereNull and whereNotNull clause then we can use this syntax `['column', '=', NULL]` and `['column', '<>', NULL]` to get the records.

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

[Shine Dezign](https://shinedezign.com/)
