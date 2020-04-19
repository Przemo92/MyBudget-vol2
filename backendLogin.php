<?php
	
	session_start();
	
	if((!isset($_POST['login'])) || (!isset($_POST['pass'])))
	{
		header('Location: loginWeb.php');
		exit();
	}
	
	require_once "connect.php";
	
	$connect = new mysqli($host, $db_user, $db_password, $db_name);
	
	if($connect->connect_errno!=0)
	{
		echo "Error".$connect->connect_errno;
	}
	else
	{
		$login = $_POST['login'];
		$password = $_POST['pass'];
		
		$login = htmlentities($login, ENT_QUOTES, "UTF-8");
		$password = htmlentities($password, ENT_QUOTES, "UTF-8");
		
		
		
		if($result = $connect->query(
		sprintf("SELECT * FROM users WHERE username='%s' AND password='%s'",
		mysqli_real_escape_string($connect,$login),
		mysqli_real_escape_string($connect,$password))))
		{
			$user_number = $result->num_rows;
			if($user_number >0)
			{
				$_SESSION['loged'] = true;
				
				$line =$result->fetch_assoc(); //tworzymy tablice line z wartosciami z bazy sql, które zwroci nam kwerenda $sql
				$_SESSION['user'] = $line['username'];
				$_SESSION['id'] = $line['id'];
				
				unset($_SESSION['wrong']);
				$result->close();
				header('Location: mainMenuWeb.php');
			}
			else{
				$_SESSION['wrong'] = '<span style= "color: red"> Nieprawidłowy login lub hasło!</span>';
				header('Location: loginWeb.php');
			}
		}
		echo "blad";
		$connect->close();
	}
?>