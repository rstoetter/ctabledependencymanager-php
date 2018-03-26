<?php

namespace rstoetter\ctabledependencymanager;

/**
  *
  * ## description
  *
  * The class cTableDependencyPath implements the dependency between two tables in a tree of the 
  * type \rstoetter\ckeycolumnusagetree\cKeyColumnUsageTree. 
  *
  * The class \rstoetter\ckeycolumnusagetree\cTableDependencyManager keeps all dependencies of the database, which refer to a 
  * certain target table in objects of the type \rstoetter\ckeycolumnusagetree\cTableDependency which manage objects of type 
  * \rstoetter\ctabledependencymanager\cTableDependencyPath among other things
  *
  * The member variable $m_a_path consists of table names. The first entry is the table, from which the dependency is starting from and
  * the last entry in $m_a_path is the table the path is referring to. Between the source table and the target table there can be lots of
  * other tables
  *
  * ## Usage:  
  *
  *  ```php
  *
  * $schema_name = 'give me the name of my database';
  * $table_name = 'give me the name of an existing table in the schema';
  *
  * // open the database
  * $mysqli = new mysqli(
  *                  'the database host',
  *                  'the database account name',
  *                  'the password of the database account',
  *                  $schema_name
  *              );
  *
  *
  * // retrieve the key column usage of the database
  * $obj_ac_key_column_usage = new \rstoetter\libsqlphp\cKEY_COLUMN_USAGE( $schema_name, $mysqli );
  *
  * // build the sorted key column usage tree of the database
  * $obj_key_column_usage_tree = new \rstoetter\ckeycolumnusagetree\cKeyColumnUsageTree( $obj_ac_key_column_usage );
  *
  * // create the table dependency manager for the table $table_name
  * $obj_table_dependency_manager = new \rstoetter\ctabledependencymanager\cTableDependencyManager( $table_name, $obj_key_column_usage_tree );
  *
  * // print the dependency paths found in the table dependency manager
  * echo "\n in the database $schema_name refer the following tables to $table_name - directly and indirectly";
  *
  * for ( $i = 0; $i < $obj_table_dependency_manager->GetTableDependencyCount( ); $i++ ) {
  *
  *     // retrieve object of type \rstoetter\ctabledependencymanager\cTableDependency
  *     $obj_table_dependency = $obj_table_dependency_manager->GetTableDependency( $i );
  *     echo "\n ";
  *     // GetPathObject( ) returns type \rstoetter\ctabledependencymanager\cTableDependencyPath
  *     echo $obj_table_dependency->GetPathObject( )->AsString( );  
  *     
  * }
  * 
  *
  *  ```
  *
  *
  * @author Rainer Stötter
  * @copyright 2018 Rainer Stötter
  * @license MIT
  * @version =1.0
  *
  */


class cTableDependencyPath {

    // a deletion path
    
    /**
      * The version of the class cTableDependencyPath
      *
      * @var string $m_version the version of the class cTableDependencyPath
      *
      *
      */
      
    public static $m_version = '1.0.0';

    /**
      * the deletion rule / the dependency between the source table and the target table
      *
      * @var array $m_a_path an array consisting of table names as string. The first table name is the source table and the last table name is the target table. Between these two tables can be other tables, too
      *
      *
      */
    

    
    public $m_a_path = array( );        // the deletion rule / the dependency between the source table and the target table


    /**
      *
      * The constructor of the class cTableDependencyPath
      *
      * @param array $a_path an array of table names as string with the dependency path between the first table name and the last table name
      *
      * @return cTableDependencyPath a new instance of cTableDependencyPath
      *
      */       
    
    
    function __construct(           // cTableDependencyPath
                    array $a_path
                        ) {  
                        
        // if ( ! strlen ( trim( $constraint_name ) ) ) die( "\n cTableDependencyPath without valid constraint name" );
        if ( count( $a_path ) < 2 ) {
            var_dump( $a_path );
            die( "\n cTableDependencyPath with invalid path" );
        }
                        
        $this->m_a_path = $a_path;
                        
    }   // function __destruct( )
    
    /**
      *
      * The destructor of the class cTableDependencyPath
      *
      */       
    
    
    function __destruct( ) {       // cTableDependencyPath
    }   // function __destruct( )
    
    /**
      *
      * returns the target table ( the last one in the managed dependency path array )
      *
      * @return string the name of the target table
      *
      */       
    
    
    
    public function TargetTable( ) : string {
        if ( count( $this->m_a_path ) ) {
            return ( $this->m_a_path[ count( $this->m_a_path ) - 1 ] );
        }
        
        return '';
        
    }   // function TargetTable( )
    
    /**
      *
      * returns the source table ( the first one in the managed dependency path array )
      *
      * @return string the name of the source table
      *
      */       
    
    
    public function ReferingTable( ) : string {
    
        if ( count( $this->m_a_path ) ) {
            return ( $this->m_a_path[ 0 ] );
        }
        
        return '';
        
    }   // function ReferingTable( )
    
    
    /**
      *
      * returns true, if the dependency path begins with the source table $refering_table_name and ends with the target table $target_table_name
      *
      * @return bool true, if the source table is $refering_table_name and the target table is $target_table_name
      *
      */       
    
    
    public function HasPathFor( $refering_table_name, $target_table_name ) : bool {
    
        return ( $this->TargetTable( ) == $target_table_name ) && ( $this->ReferingTable( ) == $refering_table_name );
        
    }   // function HasPathFor( )
    
    /**
      *
      * returns true, if the path in $a_path is part of the managed path or the same as the managed path
      *
      * @param array $a_path an array of table names as string with the dependency path between the first table name and the last table name      
      *
      * @return bool true, if $a_path is part of the managed path or the same as the managed path
      *
      */       
    
    
    public function PathIsPart( array $a_path ) : bool {
        
        if ( count( $a_path ) != count( $this->m_a_path ) )  {
            return false;
        }
        
        for ( $i = 0; $i < count( $a_path ); $i++ ) {
            if ( $a_path[ $i ] != $this->m_a_path[ $i ] ) {
                return false;
            }
        }
    
        return true;
        
    
    }   // function PathIsPart( )
    
    /**
      *
      * returns true, if the dependency path managed by $obj_path is part of the managed dependency path or the same as the managed dependency path
      *
      * @param \rstoetter\cbalancedbinarytree\cTableDependencyPath $obj_path an object managing a dependency path
      *
      * @return bool true, if the path managed by $obj_path is part of the managed path or the same as the managed path
      *
      */       
    
    
    
    public function PathObjectIsPart( cTableDependencyPath $obj_path ) : bool {
        
        return $this->PathIsPart( $obj_path->m_a_path );
        
    
    }   // function PathObjectIsPart( )
    
    /**
      *
      * returns the managed dependency path as string (ie 'table1->table2->table3' )
      *
      * @return string the path managed as string
      *
      */       
    
    public function AsString( ) : string {
    
        $ret = '';
        
        $was_here = false;
        
        foreach( $this->m_a_path as $table_name ) {
        
            if ( $was_here ) $ret .= ' -> ';
            $was_here = true;
            
            $ret .= $table_name;
        
        }
        
        return $ret;
    
    }   // function AsString( )
    
    /**
      *
      * returns the level of the managed dependency path - this is the length of the path
      *
      * @return int the level of the managed path
      *
      */       
    
    
    public function GetLevel( ) : int {
    
        return count( $this->m_a_path );
        
    }   // function GetLevel( )
    
    
    /**
      *
      * adds a new target table name to the managed dependency path
      *
      * @param string $table_name the new target table
      *
      */       

      
    public function AddToPath( string $table_name ) {
    
        $this->m_a_path[] = $table_name;
        $this->m_table_name = $table_name;
    
    }   // function AddToPath( )
    
    
    
}   // class cTableDependencyPath


/**
  *
  * ## description
  *
  * The class cTableDependency implements the dependency between two tables in a tree of the 
  * type \rstoetter\ckeycolumnusagetree\cKeyColumnUsageTree. 
  *
  * The class \rstoetter\ctabledependencymanager\cTableDependencyManager keeps all dependencies of the database, which refer to a 
  * certain target table
  *
  * The member variable $m_a_path consists of table names. The first entry is the table, from which the dependency is starting from and
  * the last entry in $m_a_path is the table the path is referring to. Between the source table and the target table there can be lots of
  * other tables
  *
  * ## Usage:  
  *
  *  ```php
  *
  * $schema_name = 'give me the name of my database';
  * $table_name = 'give me the name of an existing table in the schema';
  *
  * // open the database
  * $mysqli = new mysqli(
  *                  'the database host',
  *                  'the database account name',
  *                  'the password of the database account',
  *                  $schema_name
  *              );
  *
  *
  * // retrieve the key column usage of the database
  * $obj_ac_key_column_usage = new \rstoetter\libsqlphp\cKEY_COLUMN_USAGE( $schema_name, $mysqli );
  *
  * // build the sorted key column usage tree of the database
  * $obj_key_column_usage_tree = new \rstoetter\ckeycolumnusagetree\cKeyColumnUsageTree( $obj_ac_key_column_usage );
  *
  * // create the table dependency manager for the table $table_name
  * $obj_table_dependency_manager = new \rstoetter\ctabledependencymanager\cTableDependencyManager( $table_name, $obj_key_column_usage_tree );
  *
  * // print all table dependencies managed by the table dependency manager
  * echo "\n in the database $schema_name refer the following tables to $table_name - directly and indirectly";
  *
  * for ( $i = 0; $i < $obj_table_dependency_manager->GetTableDependencyCount( ); $i++ ) {
  *
  *     // retrieve object of type \rstoetter\ctabledependencymanager\cTableDependency
  *     $obj_table_dependency = $obj_table_dependency_manager->GetTableDependency( $i );
  *     echo "\n ";
  *     // GetPathObject( ) returns type \rstoetter\ctabledependencymanager\cTableDependencyPath
  *     echo $obj_table_dependency->GetPathObject( )->AsString( );  
  *     
  * }
  * 
  *
  *  ```
  *
  *
  * @author Rainer Stötter
  * @copyright 2018 Rainer Stötter
  * @license MIT
  * @version =1.0
  *
  */
  
class cTableDependency {


    /**
      * The version of the class cTableDependency
      *
      * @var string $m_version the version of the class cTableDependency
      *
      *
      */
      
    public static $m_version = '1.0.0';

    /**
      * The path of the dependencies managed by an object of type cTableDependencyPath
      *
      * @var \rstoetter\cbalancedbinarytree\cTableDependencyPath $m_obj_path The path of the dependencies
      *
      *
      */

    public $m_obj_path = null;    // the path of the dependencies managed by an object of type cTableDependencyPath
    
    /**
      * The source table name
      *
      * @var string $m_table_name The name of the source table
      *
      *
      */    
    
    public $m_table_name = '';        
    
    /**
      *
      * The constructor of the class cTableDependency
      *
      * @param string $table_name the name of the source table 
      * @param array $a_path an array consisting of table names as string. The first table name is the source table and the last table name is the target table. Between these two tables can be other tables, too. The path must consist of at least two elements
      *
      * @return cTableDependency a new instance of cTableDependency
      *
      */       
    
    
    
    function __construct( string $table_name, array & $a_path ) {  // cTableDependency
    
        if ( count( $a_path ) < 2 ) {
            die( "\n error when constructing cTableDependency - the path is too short!" );
        }
        
        if ( $table_name != $a_path[ 0 ] ) {
            var_dump( $a_path );
            die( "\n error when constructing cTableDependency - the path starts not with {$table_name}!" );
        }
        
    
        $this->m_table_name = $table_name;
        $this->m_obj_path = new cTableDependencyPath( $a_path );
    
    
    }   // function __construct( )
    
    /**
      *
      * Returns the managed path object
      *
      * @return \rstoetter\cbalancedbinarytree\cTableDependencyPath a pointer to the managed dependency path object
      *
      */       
    
    
    public function & GetPathObject( ) : cTableDependencyPath {
        return $this->m_obj_path;
    }   // function ReferredTable( )
    

    /**
      *
      * Returns the target table - the last table in the dependency path
      *
      * @return string the name of the target table
      *
      */       
    
    
    public function ReferredTable( ) : string {
        return $this->m_obj_path->TargetTable( );
    }   // function ReferredTable( )
    
    /**
      *
      * Returns the source table - the first table in the dependency path
      *
      * @return string the name of the source table
      *
      */           
    
    public function ReferingTable( ) : string {
        return $this->m_obj_path->ReferingTable( );
    }   // function ReferingTable( )    
    
    /**
      *
      * adds a new target table name to the managed dependency path
      *
      * @param string $table_name the new target table
      *
      */       
    
    
    
    public function AddToPath( string $table_name ) {
    
        $this->m_obj_path->AddToPath( $table_name );
        $this->m_table_name = $table_name;
        
    }   // function AddToPath( )
    
    /**
      *
      * returns true, if the path in $a_path is part of the managed path or the same as the managed path
      *
      * @param array $a_path an array of table names as string with the dependency path between the first table name and the last table name      
      *
      * @return bool true, if $a_path is part of the managed path or the same as the managed path
      *
      */       

      
    public function PathIsPart( array & $a_path ) : bool {
    
        return $this->m_obj_path->PathIsPart( $a_path );
    
    }   // function PathIsPart( )
    
    
    /**
      *
      * returns the level of the managed dependency path - this is the length of the path
      *
      * @return int the level of the managed path
      *
      */       
    
    
    public function GetLevel( ) : int {
        return $this->m_obj_path->GetLevel( );
    }   // function GetLevel( )
    
    

}   // class cTableDependency


/**
  *
  * ## description
  *
  * The class cTableDependencyManager keeps the various dependencies to a certain target table. There can be lots of dependency paths to 
  * a certain table in the database
  *
  * Actually merely dependencies of a mysql database are supported
  *
  * The class \rstoetter\ctabledependencymanager\cTableDependencyManager keeps all dependencies of the database, which refer to a 
  * certain target table
  *
  * 
  *
  * ## Usage:  
  *
  *  ```php
  *
  * $schema_name = 'give me the name of my database';
  * $table_name = 'give me the name of an existing table in the schema';
  *
  * // open the database
  * $mysqli = new mysqli(
  *                  'the database host',
  *                  'the database account name',
  *                  'the password of the database account',
  *                  $schema_name
  *              );
  *
  *
  * // retrieve the key column usage of the database
  * $obj_ac_key_column_usage = new \rstoetter\libsqlphp\cKEY_COLUMN_USAGE( $schema_name, $mysqli );
  *
  * // build the sorted key column usage tree of the database
  * $obj_key_column_usage_tree = new \rstoetter\ckeycolumnusagetree\cKeyColumnUsageTree( $obj_ac_key_column_usage );
  *
  * // create the table dependency manager for the table $table_name
  * $obj_table_dependency_manager = new \rstoetter\ctabledependencymanager\cTableDependencyManager( $table_name, $obj_key_column_usage_tree );
  *
  * // print the table dependecies managed by the table dependency manager
  * echo "\n in the database $schema_name refer the following tables to $table_name - directly and indirectly";
  *
  * for ( $i = 0; $i < $obj_table_dependency_manager->GetTableDependencyCount( ); $i++ ) {
  *
  *     // retrieve object of type \rstoetter\ctabledependencymanager\cTableDependency
  *     $obj_table_dependency = $obj_table_dependency_manager->GetTableDependency( $i );
  *     echo "\n ";
  *     // GetPathObject( ) returns type \rstoetter\ctabledependencymanager\cTableDependencyPath
  *     echo $obj_table_dependency->GetPathObject( )->AsString( );  
  *     
  * }
  * 
  *
  *  ```
  *
  *
  * @author Rainer Stötter
  * @copyright 2018 Rainer Stötter
  * @license MIT
  * @version =1.0
  *
  */


class cTableDependencyManager {

    /**
      * The version of the class cTableDependencyManager
      *
      * @var string $m_version the version of the class cTableDependencyManager
      *
      *
      */
      
    public static $m_version = '1.0.0';
    
    /**
      * The name of the target table to which the table dependencies are referring to
      *
      * @var string $m_table_name the name of the target table
      *
      *
      */    

    public $m_table_name = '';
    
    /**
      * An Array consisting of objects of the type \rstoetter\ctabledependencymanager\cTableDependency
      *
      * @var array $m_a_entries the various dependency paths referring to $this->m_table_name
      *
      *
      */      
    
    public $m_a_entries = array( );                     // the entries we manage from type cTableDependency
    
     /**
      * An object of type \rstoetter\ckeycolumnusagetree\cKeyColumnUsageTree
      *
      * @var \rstoetter\ckeycolumnusagetree\cKeyColumnUsageTree $m_obj_key_column_usage_tree the key column usage tree of the database
      *
      *
      */     
      
    protected $m_obj_key_column_usage_tree = null;    // the usage tree 
    

    /**
      *
      * The constructor of the class cTableDependencyManager
      *
      * @param string $table_name the name of the target table (where the managed objects of type cTableDependency are referring to)
      * @param \rstoetter\ckeycolumnusagetree\cKeyColumnUsageTree $obj_key_column_usage_tree the key column usage tree of the database 
      *
      * @return cTableDependencyManager a new instance of cTableDependencyManager
      *
      */       
    
    
    function __construct( 
                    string $table_name,
                    \rstoetter\ckeycolumnusagetree\cKeyColumnUsageTree $obj_key_column_usage_tree
                    // \rstoetter\libsqlphp\cKEY_COLUMN_USAGE $obj_ac_key_column_usage 
                    ){        // cTableDependencyManager
    
    
        $this->m_table_name = $table_name;
        $this->m_obj_key_column_usage_tree = $obj_key_column_usage_tree;
        // $this->m_obj_ac_key_column_usage = $obj_ac_key_column_usage;
        
        //
        $a_paths = array( );
        $this->m_obj_key_column_usage_tree->CollectAllDependencyPaths( $a_paths, $this->m_table_name );         
        
        foreach( $a_paths as $a_path ) {
            $this->m_a_entries[] = new cTableDependency( $a_path[ 0 ], $a_path );
        }
        
        $a_paths = null;
    
    }   // function __construct( )
    
    /**
      *
      * The destructor of the class cTableDependencyManager
      *
      *
      */       
    
    
    function __destruct( ){        // cTableDependencyManager
    
    }   // function __destruct( )
    
    
    /**
      *
      * Returns the used key column usage tree
      *
      * @return \rstoetter\ckeycolumnusagetree\cKeyColumnUsageTree the key column usage tree of the database 
      *
      */       
    
    
    public function & GetTreeObject( ) : \rstoetter\ckeycolumnusagetree\cKeyColumnUsageTree {
        return $this->m_obj_cKeyColumnUsageTree;
    }   // function GetTreeObject( )

    
    /**
      *
      * Returns true, if there is a dependency between the table with the name $referrer and the table $referenced_table_name
      *
      * @param string $referrer the name of the source table which could refer to the target table $referenced_table_name (perhaps with some table dependencies between)
      * @param string $referenced_table_name the target table 
      *
      * @return bool true, if there is a dependency from $referrer to $referenced_table_name
      *
      */       
    
    
    public function RefersTo( string $referrer, string $referenced_table_name) : bool {
        
        foreach( $this->m_a_entries as $obj_table_dependency) {
        
            if ( in_array( $referrer, $obj_table_dependency->GetPathObject( )->m_a_path ) ) {
                if ( in_array( $referenced_table_name, $obj_table_dependency->GetPathObject( )->m_a_path ) ) {
                
                    $pos_referrer = 0;
                    $pos_referenced_table_name = 0;
                    
                    for ( $i = 0; $i < count( $obj_table_dependency->GetPathObject( )->m_a_path ); $i++ ) {
                        if ( $obj_table_dependency->GetPathObject( )->m_a_path[ $i ] == $referrer ) $pos_referrer = $i;
                        if ( $obj_table_dependency->GetPathObject( )->m_a_path[ $i ] == $referenced_table_name ) $pos_referenced_table_name = $i;                        
                        if ( ( $pos_referrer != 0 ) && ( $pos_referenced_table_name != 0 ) ) break;
                    }
                    
                    return ( $pos_referrer < $pos_referenced_table_name );
                
                }
            }
        }
        
        return false;
    
    }   // function RefersTo( )
    
    /**
      *
      * Returns the number of table dependencies we are managing
      *
      * @return int the number of objects of the type \rstoetter\ctabledependencymanager\cTableDependency in $this->m_a_entries
      *
      */       
    
    
    public function GetTableDependencyCount( ) : int {
        return count( $this->m_a_entries );
    }   // function GetTableDependencyCount( )
    
    /**
      *
      * Returns the dependency with the index $index in $this->m_a_entries
      *
      * @param int $index the index of the item we wanna get      
      *
      * @return \rstoetter\ctabledependencymanager\cTableDependency the item with the index $index of $this->m_a_entries
      *
      */       
    
    
    public function GetTableDependency( int $index ) : cTableDependency {
        
        if ( ( $index < 0 ) || ( $index < count( $this->m_a_entries ) ) ) die( "\n cTableDependencyManager: Wrong index {$index}" );
        
        return $this->m_a_entries[ $i ];
    
    }   // function GetDependencyCount( )
    
    
    /**
      *
      * Returns an array with the table names referring to $this->m_table_name
      *
      * @param array $ary the array which should be filled with the referencing table names 
      *
      */       
    
    
    public function GetAllReferencingTables( array & $ary ) {
    
            $ary = array( );
    
            foreach ( $this->m_a_entries as $obj_table_reference ) {
                $ary[] = $obj_table_reference->m_table_name;
            }

    
    }   // function GetAllReferencingTables( )
    
    

}   // class cTableDependencyManager
?>