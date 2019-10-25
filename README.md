# MySQLi Easy to use by Hardweb.it
Sometimes you need for an easy way to start your PHP project with a full set of CRUD (Create, Read, Update, Destroy) functions.
With this script you can CRUD your database in few seconds, with the binding security to avoid the most part of SQL injection.

## Current version
1.4

## Changelog
#### 1.4
+ Simplified and reduced codes
+ Add constants to set the db connection instead array
+ Readme completed
#### 1.3
+ renamed functions
+ deleted unused functions
+ rewrited examples
#### 1.2
+ added mysqli_create_database($database_name) -> easily create a database
+ added mysqli_easy_query($query) -> any unbinded query
#### 1.1
+ added mysqli_delete_data($query)
+ added +mysqli_create_table($query)
#### 1.0
+ First Stable release


## LIST OF DB FUNCTIONS

1. **mysqli_easy_query** (for all unsecure queries, like: ALTER, DROP, CREATE TABLE)
2. **mysqli_create_database** (for CREATE DATABASE query)
3. **mysqli_write** (for INSERT or UPDATE query)
4. **mysqli_read** (for SELECT query)
5. **mysqli_verify_connection** (to check the db connection)

## CONFIGURE THE CONNECTION

#### DB connection configuration
1. Replace 'localhost' with your hostname or IP address
2. Replace 'database_name' with your.
3. Replace 'your_db_username' with the username which have access and privileges to operate on database
4. Replace 'your_db_passwrd' with the username relative password.

`
	define('HOST', 'localhost');
	define('DB_NAME', 'database_name');
	define('DB_USER', 'your_db_username');
	define('DB_PASS', 'your_db_password');
`

#### DEBUG
If you need for debug information, while testing the queries, you can replace 'return false' with 'return true'.

	function hw_debug() {
		//Set to TRUE if you need debug output
		return false;
	}

## EXAMPLES
All functions return TRUE if query was executed without errors, else they will return FALSE

## mysqli_easy_query

EXAMPLE 1: Simple ALTER TABLE with ADD one column datatype
  
	$query = "ALTER TABLE table_name ADD column_name integer";
	$execute_query = mysqli_easy_query($query); //Return true or false

## mysqli_create_database

EXAMPLE 1:
	$database_name = "books";
	$execute_query = mysqli_create_database($database_name);

## mysqli_write

EXAMPLE 1: With values in array.
  
	$values = array("foo1","foo2"); 
	$query = "UPDATE table_name SET campo1=?, campo2=? WHERE id=21"; 
	$execute_query = mysqli_write($values, $query); //Return true or false
    
EXAMPLE 2: No array, direct values in query
	
	$execute_query = mysqli_write(array("valore1","valore2"), "INSERT INTO table_name (campo1, campo2) VALUES (?,?)"); //Return true or false

## mysqli_read
Explanation of $values array indexes:

	$value[row_number][column_number]

EXAMPLE 1: With values in array
	
	$query = "SELECT id, campo1, campo2 FROM table_name";
	$values = mysqli_read($query);
    
EXAMPLE 2: No array, direct values in query
	
	$values = mysqli_read("SELECT id, campo1, campo2 FROM table_name");
		
 HOW TO READ single row from $values
 
	$id = $values[1][1];
	$campo1 = $values[1][2];
	$campo2 = $values[1][3];
	echo "$id, $campo1, $campo2";
			
HOW TO READ multiple rows $values

	$n_row = count($values);
	$curr_row=1;
	while ($curr_row <= $n_row) {
	$id = $values[$curr_row][1];
	$campo1 = $values[$curr_row][2];
	$campo2 = $values[$curr_row][3];
	$curr_row++;
	echo "$id, $campo1, $campo2 <br>";
			
      

			
			
## Note for Variables in Queries
Remember to use single quotes when you make your queries:
For example if you have a variable like:
	
	$name = "foo";
USE

	$values = mysqli_read("SELECT id FROM table_name WHERE name='$name'"); //is correct

INSTEAD

	$values = mysqli_read("SELECT id FROM table_name WHERE name=$name"); //is wrong
