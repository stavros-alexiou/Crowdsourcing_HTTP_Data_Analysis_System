<?php
session_start();
include "db_conn.php";

if (isset($_POST['uname']) && isset($_POST['password'])){

	function validate($data){
		$data = trim($data); //remove spaces
		$data = stripslashes($data); //remove quotes -> read as text
		$data = htmlspecialchars($data);
		return $data;
	}

	$uname = validate($_POST['uname']);
	$pass = validate($_POST['password']);

	
	if(empty($uname)){
		header("Location: index.php?error=User Name is required.");
		exit();

	} else if(empty($pass)){
		header("Location: index.php?error=Password is required.");
		exit();

	} else {
		//hashing the password
		//$pass = md5($pass);

		$result = mysqli_query($conn, "SELECT * FROM person WHERE userName='$uname' AND password='$pass' ");
		
		if(mysqli_num_rows($result) === 1) {
			$row = mysqli_fetch_assoc($result);
			if($row['userName'] === $uname && $row['password'] === $pass){
				$_SESSION['userName'] = $row['userName'];
				$_SESSION['email'] = $row['email'];
				$_SESSION['userID'] = $row['userID'];
				$_SESSION['idAdmin'] = $row['isAdmin'];
				
				if($row['isAdmin']){
					header("Location: adminHome.php");
					exit();
				} else{
					header("Location: home.php");
					exit();
				}

			}else{
				header("Location: index.php?error=Incorrect username or password.");
				exit();
			}

		} else{
			header("Location: index.php?error=Incorrect username or password.");
			exit();
		}
	}

} else{
	header("Location: index.php");
	exit();
}