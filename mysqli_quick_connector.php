<?php
/*
MySQLI functions for HW-Framework
Version: 1.3
Last Update: 15/03/2017
Author: Marzaro Gianluca
Contact: info@hardweb.it
Web: http://hardweb.it

Changelog:
= 1.3 =
+-renamed functions
-deleted unused functions
+rewrited examples
= 1.2 =
+added mysqli_create_database($database_name) -> easily create a database
+added mysqli_easy_query($query) -> any unbinded query
= 1.1 =
+added mysqli_delete_data($query)
+added +mysqli_create_table($query)
= 1.0 =
Tested ok. script work.
*/
?>
<?php
/* START LIST OF FUNCTIONS */
/*
1) mysqli_easy_query (FOR ALL not secure statement like: ALTER, DROP, CREATE TABLE, others)
2) mysqli_create_database (for CREATE DATABASE statement, USE native mysqli_query function)
3) determine variable type for mysqli_write (by passing $var, return the var type as integer, string, double, object or null)
4) mysqli_write (for INSERT or UPDATE statement)
5) mysqli_read (for SELECT statement)
6) mysqli_verify_connection (for check db connection)
*/
/* END LIST OF FUNCTIONS */

////////////////////////////////////////////////////////////////////////////
/* START mysqli_easy_query EXAMPLES
	/* EXAMPLE 1: Simple ALTER TABLE with ADD one column datatype
	$query = "ALTER TABLE table_name ADD column_name integer";
	$execute_query = mysqli_easy_query($query); //Return true or false

/* END mysqli_easy_query EXAMPLES */
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
/* START mysqli_create_database EXAMPLE

	/* EXAMPLE 1:

		$database_name = "books";
		$execute_query = mysqli_create_database($database_name);
		
		//AFTER CALLING the function AN ECHO WILL PRINT THE RESULT!
		
/* END mysqli_create_database EXAMPLE */
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
/* START mysqli_write EXAMPLES

	/* EXAMPLE 1: With values in array.

		$values = array("foo1","foo2"); 
		$query = "UPDATE table_name SET campo1=?, campo2=? WHERE id=21"; 
		$execute_query = mysqli_write($values, $query); //Return true or false


	/* EXAMPLE 2: No array, direct values in query

		$execute_query = mysqli_write(array("valore1","valore2"), "INSERT INTO table_name (campo1, campo2) VALUES (?,?)"); //Return true or false
	
/* END mysqli_write EXAMPLES */
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
/* START mysqli_read EXAMPLES

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
			
			
	/* NOTE FOR Variables in Queries */
	// Must use '$var' to perform the queries, example:
	// $name = "foo";
	// $values = mysqli_read("SELECT id FROM table_name WHERE name='$name'");
	// ELSE the function will return an error.
			
/* END mysqli_read EXAMPLES */
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////

//INCLUDES
//Configurazione Database
function mysqli_configuration() {

//Inserire l'indirizzo del tuo server mysql
$db_connection['server'] = "localhost";
//Inserire il nome del database
$db_connection['db_name'] = "your_db_name";
//Inserire l'username del database
$db_connection['username'] = "your_db_username";
//Inserire la password del database
$db_connection['password'] = "your_db_password";
return $db_connection;
}

//1-Function for ALL OTHER SQL STATEMENT, which not require secure binding.
function mysqli_easy_query($query) {
	$db_connection = mysqli_configuration();
	$mysqli = new mysqli($db_connection['server'], $db_connection['username'], $db_connection['password'], $db_connection['db_name']) or die(mysqli_error($mysqli));
	
	$result = $mysqli->query($query);
	
	$mysqli->close();
	return $result;
}

//2-Function for CREATE DATABASE statement
function mysqli_create_database($database_name) {
$db_connection = mysqli_configuration();
$con=mysqli_connect($db_connection['server'],$db_connection['username'],$db_connection['password']);
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

//3-Function for CREATE DATABASE statement
$sql="CREATE DATABASE $database_name";
if (mysqli_query($con,$sql))
  {
  echo "Database $database_name created successfully";
  }
else
  {
  echo "Error creating database: " . mysqli_error($con);
  }
}

//3-Determine variables type
function types($values) {

$k = 0;
$types = array(0 => "");

foreach ($values as $value) {

$vartype = gettype($value);
	
	Switch ($vartype) {
	
		case "integer" :
		$types[$k] = "i";
		break;
		
		case "double" :
		$types[$k] = "d";
		break;
		
		case "string" :
		$types[$k] = "s";
		break;
		
		case "object" :
		$types[$k] = "b";
		break;
		
		case "NULL" :
		$types[$k] = "s";
		break;
		
		default :
		$types[$k] = "s";
		
		}
		$k++;
		
    //echo gettype($value) . "<br>";
}

return $types;
}

//4-Function for secure INSERT or UPDATE sql statement.
function mysqli_write($values, $query) {
$db_connection = mysqli_configuration();
$sql_link = mysqli_connect($db_connection['server'], $db_connection['username'], $db_connection['password'], $db_connection['db_name']);
$sql_stmt = mysqli_prepare($sql_link, $query);

        $refs = array();
        foreach($values as $key => $value) { 
            $refs[$key] = &$values[$key]; 
		}
		if (hw_debug()){
		print_r($refs);
		}

/* convert array to string and insert string in a single index 0 array */
$types = types($values);
$string_types = "";
foreach ($types as $type) {
	$string_types .= $type;
}
$string_types = array(0 => $string_types);
if (hw_debug()) { 
print_r($string_types);
}

call_user_func_array(array($sql_stmt, 'bind_param'), array_merge($string_types, $refs)); 
/* Execute the statement */
$execute = mysqli_stmt_execute($sql_stmt); //return TRUE on success, else return FALSE

/* close statement */
mysqli_stmt_close($sql_stmt);
return $execute;
}

//5-Function for SELECT sql statement, returning a single multiple-rows Array.
function mysqli_read($query) {
	$db_connection = mysqli_configuration();
	$mysqli = new mysqli($db_connection['server'], $db_connection['username'], $db_connection['password'], $db_connection['db_name']) or die(mysqli_error($mysqli));
	$result = $mysqli->query($query);
	$r=1;
	$output = array();
	//print_r($mysqli);
	//echo "<br><br>";
	if ($mysqli->affected_rows >= 0) {
		while ($row = $result->fetch_row()) { 

			$c=1;
				foreach ($row as $col) {
				$output[$r][$c] = $col;
				$c++;
				}
		$r++;
		}
		$result->close();
		$mysqli->close();
	
	} else {
	echo "Errore in questa query $query<br>";
	}
	
	//print_r($output);
	if ($result) {
	return $output;
	} else {
	return $result;
	}
}

//6-Function for CHECK DATABASE CONNECTION
function mysqli_verify_connection($first_check=false) {
	$db_connection = mysqli_configuration();
	@$mysqli = new mysqli($db_connection['server'], $db_connection['username'], $db_connection['password'], $db_connection['db_name']) or die(mysqli_error($mysqli));
	
    //print_r($mysqli);
	//echo "<br><br>";

	$error = $mysqli->connect_errno;
	if ($error == '1045') {
	echo "Username o password database errate<br>";
	return false;
	} elseif ($error == '1044') {
	echo "Il database <span class='bold'>".$db_connection['db_name']."</span> non esiste<br>";
	return false;
	} elseif (empty($db_connection['db_name'])) {
		if ($first_check==false) { echo "Il nome del database non può essere vuoto!<br>";}
		return false;
	} elseif (empty($db_connection['server']) || $error == '2002') {
		if ($first_check==false) { echo "L'indirizzo dell'host è errato o non risponde!<br>";}
		return false;
	} else {
	return true;
	}
}
?>