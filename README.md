# MySQLi Easy to use by Hardweb.it

## Current version
1.3

## Changelog
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


## LIST OF FUNCTIONS

1) mysqli_easy_query (FOR ALL not secure statement like: ALTER, DROP, CREATE TABLE, others)
2) mysqli_create_database (for CREATE DATABASE statement, USE native mysqli_query function)
3) determine variable type for mysqli_write (by passing $var, return the var type as integer, string, double, object or null)
4) mysqli_write (for INSERT or UPDATE statement)
5) mysqli_read (for SELECT statement)
6) mysqli_verify_connection (for check db connection)

## mysqli_easy_query (function examples)

	/* EXAMPLE 1: Simple ALTER TABLE with ADD one column datatype
  
	$query = "ALTER TABLE table_name ADD column_name integer";
	$execute_query = mysqli_easy_query($query); //Return true or false

## mysqli_create_database (function examples)

	/* EXAMPLE 1:
		$database_name = "books";
		$execute_query = mysqli_create_database($database_name);

## mysqli_write (function examples)

	/* EXAMPLE 1: With values in array.
  
		$values = array("foo1","foo2"); 
		$query = "UPDATE table_name SET campo1=?, campo2=? WHERE id=21"; 
		$execute_query = mysqli_write($values, $query); //Return true or false
    
	/* EXAMPLE 2: No array, direct values in query
		$execute_query = mysqli_write(array("valore1","valore2"), "INSERT INTO table_name (campo1, campo2) VALUES (?,?)"); //Return true or false

## mysqli_read (function examples)

	/* EXAMPLE 1: With values in array.
		$query = "SELECT id, campo1, campo2 FROM table_name";
		$values = mysqli_read($query);
    
	/* EXAMPLE 2: No array, direct values in query
	
		$values = mysqli_read("SELECT id, campo1, campo2 FROM table_name");
		/* HOW TO READ single row from $values
			$id = $values[1][1];
			$campo1 = $values[1][2];
			$campo2 = $values[1][3];
			echo "$id, $campo1, $campo2";
		/* HOW TO READ multiple rows $values
			$n_row = count($values);
			$curr_row=1;
			while ($curr_row <= $n_row) {
			$id = $values[$curr_row][1];
			$campo1 = $values[$curr_row][2];
			$campo2 = $values[$curr_row][3];
			$curr_row++;
			echo "$id, $campo1, $campo2 <br>";
			}
      
		/* Explanation of $values array indexes:
			$value[row_number][column_number]
			
			
## NOTE FOR Variables in Queries
Must use '$var' to perform the queries, example:
$name = "foo";
$values = mysqli_read("SELECT id FROM table_name WHERE name='$name'");
ELSE the function will return an error.
