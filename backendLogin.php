<?php
	
	session_start();
	
	if((!isset($_POST['login'])) || (!isset($_POST['pass'])))
	{
		header('Location: loginWeb.php');
		exit();
	}
	
	require_once "connect.php";
	
	mysqli_report(MYSQLI_REPORT_STRICT);
		
	try 
	{
		$connect = new mysqli($host, $db_user, $db_password, $db_name);
		
		if($connect->connect_errno!=0)
		{
			throw new Exception(mysqli_connect_errno());
		}
		else
		{
			$login = $_POST['login'];
			$password = $_POST['pass'];
			
			$login = htmlentities($login, ENT_QUOTES, "UTF-8");	
			
			
			if($result = $connect->query(
			sprintf("SELECT * FROM users WHERE username='%s'",
			mysqli_real_escape_string($connect,$login))))
			{
				$user_number = $result->num_rows;
				if($user_number >0)
				{
					$line = $result->fetch_assoc(); //tworzymy tablice line z wartosciami z bazy sql, które zwroci nam kwerenda $sql
					
					if(password_verify($password, $line['password']))
					{
						$_SESSION['loged'] = true;
						
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
				else{
					$_SESSION['wrong'] = '<span style= "color: red"> Nieprawidłowy login lub hasło!</span>';
					header('Location: loginWeb.php');
				}
			}
			else
			{
				throw new Exception($polaczenie->error);
			}
			$connect->close();
		}
	}
	catch(Exception $e)
	{
		echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o wizytę w innym terminie!</span>';
		//echo '<br />Informacja developerska: '.$e;
	}
?>