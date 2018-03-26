# The repository \\rstoetter\\ctabledependencymanager-php

## description  

The class cTableDependencyManager is the main class of the repository \\rstoetter\\ctabledependencymanager-php.

The class cTableDependencyManager keeps the various dependencies to a certain target table. There can be lots of dependency paths to a certain table in the database. The dependency paths can consist of many tables, which establish the dependency between the source table and the target table. Self-referencing tables are considered, too.
 
Actually merely dependencies of a mysql database are supported

The class \\rstoetter\\ctabledependencymanager\\cTableDependencyManager keeps all dependencies of the database, which refer to a certain target table

## dependency paths

Such a dependency path could be the following one:

TST_AUF_ITP_Stichwort_CLIENT_CLIENT -> TST_AUF_ITP_Stichwort_CLIENT -> TST_AUF_ITP_Stichwort -> TST_AUF_ITP_Stichwort -> ITP_Stichwort -> ITP_Zeitraum -> Klient -> Auswahl -> AuswahlTyp -> BUCHUNGSKREIS -> MANDANT

* Here the source table TST_AUF_ITP_Stichwort_CLIENT_CLIENT depends on the table MANDANT ( the target table, the last one) 
* all the tables mentioned before MANDANT refer to MANDANT in a certain way, too - in the given dependency order from right to left
* the dependency path shows, how the tables are connected in the database with FOREIGN KEYs
* The table TST_AUF_ITP_Stichwort is a self referencing table, therefore it is mentioned twice in the dependency path

## How can I use it?

For example, if we want to do a cascading delete in the database and want to delete a record from the table MANDANT, then we have to
delete the dependencies in the database, which refer to the table MANDANT in any way.

First we have to delete affected records from TST_AUF_ITP_Stichwort_CLIENT_CLIENT, then from TST_AUF_ITP_Stichwort_CLIENT, then from
TST_AUF_ITP_Stichwort and so on, until we can delete the record in MANDANT without offending a constraint in the database.

The class cTableDependencyManager checks the whole database for the constraints between the tables and solves the order of the cascading delete.

It is a powerful weapon in order to ease the burden to keep track of all dependencies in your database. 

**BUT BE CAREFUL WHEN USING IT AS IT WOULD HELP TO DELETE ALL RECORDS FROM THE DATABASE WHICH DEPEND ON THE RECORD YOU WANT TO DELETE 
IN THE TARGET TABLE**

## helper classes

There are some helper classes, which are significantly involved in adding functionality to the class cTableDependencyManager:

* The class **cTableDependency** implements one dependency between two tables

* The class cTableDependency uses the class **cTableDependencyPath**, which implements the dependency between two tables and provides the table dependency path

You will need PHP 7 or later to use this repository

## Usage:  

```php

$schema_name = 'give me the name of my database';
$table_name = 'give me the name of an existing table in the schema';

// open the database
$mysqli = new mysqli(
                 'the database host',
                 'the database account name',
                 'the password of the database account',
                 $schema_name
             );


// retrieve the key column usage of the database
$obj_ac_key_column_usage = new \rstoetter\libsqlphp\cKEY_COLUMN_USAGE( $schema_name, $mysqli );

// build the sorted key column usage tree of the database
$obj_key_column_usage_tree = new \rstoetter\ckeycolumnusagetree\cKeyColumnUsageTree( $obj_ac_key_column_usage );

// create the table dependency manager for the table $table_name
$obj_table_dependency_manager = new \rstoetter\ctabledependencymanager\cTableDependencyManager( $table_name, $obj_key_column_usage_tree );

// print the table dependecies for the table $table_name managed by the table dependency manager
echo "\n in the database $schema_name refer the following tables to $table_name - directly and indirectly";

for ( $i = 0; $i < $obj_table_dependency_manager->GetTableDependencyCount( ); $i++ ) {

    // retrieve object of type \rstoetter\ctabledependencymanager\cTableDependency
    $obj_table_dependency = $obj_table_dependency_manager->GetTableDependency( $i );
    echo "\n ";
    // GetPathObject( ) returns type \rstoetter\ctabledependencymanager\cTableDependencyPath
    echo $obj_table_dependency->GetPathObject( )->AsString( );  
    
}


```


## Installation

This project assumes you have composer installed. Simply add:

"require" : {

    "rstoetter/ctabledependencymanager-php" : ">=1.0.0"

}

to your composer.json, and then you can simply install with:

composer install

## Namespace

Use the namespace \rstoetter\ctabledependencymanager in order to access the classes provided by the repository ctabledependencymanager-php

## More information

See the [project wiki of ctabledependencymanager-php](https://github.com/rstoetter/ctabledependencymanager-php/wiki) for more technical informations.


