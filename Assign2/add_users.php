<?php
add_user($conn, "user", "userpass"); 
add_user($conn, "admin", "adminpass");

function add_user($conn, $username, $password){ //add initial users to db with hashed passwords
	$pass_hash = password_hash($password, PASSWORD_DEFAULT);
	$sql = "INSERT INTO users(username, password) VALUES(?,?);"
	if($stmt = $conn->prepare($sql) { //if statement succeds
    	$stmt->bind_param("ss", $username, $pass_hash);
    	$stmt->execute();
    	$stmt->close();
    }
	else {
    	$error = $conn->errno . " " . $conn->error;
	}
}

?>