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
mv modules/addons/sampleaddon/ modules/addons/youraddonname/
#for registrars module try this
mv modules/addons/sampleaddon/ modules/registrars/yourregistrarname/
#for servers module try this
mv modules/addons/sampleaddon/ modules/servers/yourservername/
```
Open the DBCLass.php file from `modules/{ModuleType}/{ModuleName}/lib/` folder.

Rename line no. 7 `namespace WHMCS\Module\Addons\SampleAddon;` with `namespace WHMCS\Module\{ModuleType}\{ModuleName};`

After that, move the modules to your WHMCS modules.
```
mv modules /path/to/whmcs/modules/
```

To invoke the class methods, simply add the "use" statement in your module files:

```
use WHMCS\Module\{ModuleType}\{ModuleName}\DBClass;
```

## Usage

1. To create a single table

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

DBClass::createSchema($schema);
```

2. To create multiple tables

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

DBClass::createSchema($schema);
```
3. To insert values in table

```python
$values = [
    'name'      =>  'John Doe',
    'email'     =>  'johndoe@example.com',
    'password'  =>  md5('123456'),
    'phone'     =>  '1234567890'
];

DBClass::insertData('users', $values);
```
4. To retrieve all data

```python
DBClass::getResult('users');
```

5. To retrieve one row with where clause

```python
$where = [
    ['id', '=', 10]
];

$result = DBClass::getRow('users', $where);
```

6. To retrieve multiple rows with multiple where clause

```python
$where = [
    ['name', 'like', '%J%'],
    ['phone', '=', '1235467890']
];

$result = DBClass::getResult('users', $where);
```

7. To update table

```python
$where = [
    ['userid', '=', 10],
    ['roleid', '=', 2]
];

$values = [
    'roleid' => 3,
    'userid' => 5
];

$result = DBClass::updateData('roleusers', $values, $where);
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
