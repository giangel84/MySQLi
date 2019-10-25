<?php
/*
MySQLI functions for HW-Framework
Version: 1.3
Last Update: 15/03/2017
Author: Marzaro Gianluca
Contact: info@hardweb.it
Web: http://hardweb.it
*/

# DB connection configuration
define('HOST', 'localhost');
define('DB_NAME', 'database_name');
define('DB_USER', 'your_db_username');
define('DB_PASS', 'your_db_password');

function hw_debug() {
	//Set to TRUE if you need debug output
	return false;
}

function mysqli_easy_query($query) {
	
	$mysqli = new mysqli(HOST, DB_USER, DB_PASS, DB_NAME) or die(mysqli_error($mysqli));
	$result = $mysqli->query($query);
	$mysqli->close();
	return $result;
}

function mysqli_create_database($database_name) {

	$con=mysqli_connect(HOST,DB_USER,DB_PASS);
	$sql = "CREATE DATABASE IF NOT EXISTS $database_name";
	$result = $mysqli->query();
	
	if (mysqli_query($con,$sql)) {
	  echo "Database $database_name created successfully";
	} else {
	  echo "Error while creating database: " . mysqli_error($con);
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

$sql_link = mysqli_connect(HOST, DB_USER, DB_PASS, DB_NAME);
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

function mysqli_read($query) {
	
	$mysqli = new mysqli(HOST, DB_USER, DB_PASS, DB_NAME) or die(mysqli_error($mysqli));
	$result = $mysqli->query($query);
	$r=1;
	$output = array();

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

function mysqli_verify_connection($first_check=false) {
	
	@$mysqli = new mysqli(HOST, DB_USER, DB_PASS, DB_NAME) or die(mysqli_error($mysqli));

	$error = $mysqli->connect_errno;
	if ($error == '1045') {
	echo "Username o password database errate<br>";
	return false;
	} elseif ($error == '1044') {
	echo "Il database <span class='bold'>".DB_NAME."</span> non esiste<br>";
	return false;
	} elseif (empty(DB_NAME)) {
		if ($first_check==false) { echo "Il nome del database non può essere vuoto!<br>";}
		return false;
	} elseif (empty(HOST) || $error == '2002') {
		if ($first_check==false) { echo "L'indirizzo dell'host è errato o non risponde!<br>";}
		return false;
	} else {
	return true;
	}
}
?>
