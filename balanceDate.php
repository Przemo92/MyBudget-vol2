<?php
	
	session_start();
	
	if((!isset($_SESSION['user'])))
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

			$_SESSION['datka']= $_POST['datka'];

			$_SESSION['textDate'] = $_POST['textDate'];

			$_SESSION['datka2']= $_POST['datka2'];

			$connect->close();
		}
		
	}
	catch(Exception $e)
	{
		echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o wizytę w innym terminie!</span>';
		//echo '<br />Informacja developerska: '.$e;
	}
	
?>