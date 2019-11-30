<?php
require_once('db_connect.php');


if (isset($_POST['submit-login'])){ // login form subitted
	$username = $_POST['username'];
	$password = $_POST['password'];

	if (empty($username) || empty($password)){ // check for empty fields
		header("Location: index.php?error=emptyfields"); // redirect to main page
		die();
	}
	else{
		$sql = "SELECT * FROM users WHERE username=?;"; //check username exists 
		if ($stmt = $conn->prepare($sql)){ //if statement succeds
    		$stmt->bind_param("s", $username); 
    		$stmt->execute();
    		$stmt->bind_result($id, $username, $hased_password);
    		if ($stmt->fetch()){
    			$psswdmatch = password_verify($password, $hased_password);
    			if($psswdmatch){ //password is valid
    				session_start();
    				$_SESSION['id'] = $id;
    				$_SESSION['username'] = $username;
    				header("Location: index.php?login=success");
    				die();
    			}
    			else { // password is invalid
    				header("Location: index.php?error=invalidpassword");
    				die();
    			}
    		}
    		else{
    			header("Location: index.php?error=invalidusername");
    			die();
    		}
    	}
    	else{
    		header("Location: index.php?error=".$conn->error);
    		die();

    	}
	}
}



?>
