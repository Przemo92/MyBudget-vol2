<?php
	
	session_start();
	
	if(isset($_POST['username']))
	{
		//ustawienie flagi udanej walidacji
		$all_OK = true;
		//poprawnosc username
		$username = $_POST['username'];
		if((strlen($username)<3) || (strlen($username)>20))
		{
			$all_OK = false;
			$_SESSION['e_username'] = '<div class="input-group mb-3" style= "color: red">Nazwa użytkownika musi posiadać od 3 do 20 znaków!</div>';
		}
		if(ctype_alnum($username) == false)
		{
			$all_OK = false;
			$_SESSION['e_username'] = '<div class="input-group mb-3" style= "color: red">Nazwa użytkownika może składać się tylko z liter i cyfr (bez polskich znaków)!</div>';
		}
		
		//poprawnosc email
		
		$email = $_POST['email'];
		$emailS = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		if((filter_var($emailS, FILTER_VALIDATE_EMAIL) == false) || ($emailS != $email))
		{
			$all_OK = false;
			$_SESSION['e_email'] = '<div class="input-group mb-3" style= "color: red">Podaj poprawny adres e-mail!</div>';
		}
		
		// poprawnosc hasla
		$password1 = $_POST['pass1'];
		$password2 = $_POST['pass2'];
		
		if((strlen($password1)<8) || (strlen($password1)>20))
		{
			$all_OK = false;
			$_SESSION['e_pass'] = '<div class="input-group mb-3" style= "color: red">Hasło musi posiadać od 8 do 20 znaków!</div>';
		}
		if($password1!=$password2)
		{
			$all_OK = false;
			$_SESSION['e_pass'] = '<div class="input-group mb-3" style= "color: red">Podane hasła nie są identyczne!</div>';
		}	
		
		$password_hash = password_hash($password1, PASSWORD_DEFAULT);
		// zapamietaj wprowadzone dane
		//$_SESSION['f_username'] = $username;
		//$_SESSION['f_email'] = $email;
		//$_SESSION['f_pass1'] = $password1;
		//$_SESSION['f_pass2'] = $password2;
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
				//Czy email istnieje?
				$result = $connect->query("SELECT id FROM users WHERE email = '$email'");
				
				if(!$result) throw new Exception ($connect->error);
					
				$number_of_emails = $result->num_rows;
				
				if($number_of_emails>0)
				{
					$all_OK = false;
					$_SESSION['e_email'] = '<div class="input-group mb-3" style= "color: red">Istnieje już konto przypisane do tego adresu e-mail!</div>';
				}	
				
				//Czy username istnieje?
				$result = $connect->query("SELECT id FROM users WHERE username = '$username'");
				
				if(!$result) throw new Exception ($connect->error);
					
				$number_of_usernames = $result->num_rows;
				
				if($number_of_usernames>0)
				{
					$all_OK = false;
					$_SESSION['e_username'] = '<div class="input-group mb-3" style= "color: red">Istnieje już konto z podaną nazwą użytkownika. Wybierz inny!</div>';
				}
				if($all_OK == true)
				{
					// walidacja udana
					if($connect->query("INSERT INTO users VALUES (NULL, '$username', '$password_hash', '$email')"))
					{
						$_SESSION['registered'] = true;
						header('Location: loginWeb.php');
					}
					else
					{
						throw new Exception ($connect->error);
					}
				}
				
				$connect->close();
			}
		}
		catch(Exception $e)
		{
			echo '<div style= "color: red">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</div>';
			echo $e;
		}
		
	}
?>	
<!DOCTYPE html>
<html lang="pl">
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title>MyBudget</title>
	<meta name="description" content="Tutaj w łatwy sposób zapanujesz nad swoim domowym budżetem!">
	<meta name="keywords" content="finanse, budżet, pieniądze, jak zapanować, oblicz, wydatki, przychody, bilans, policz swoje finanse, miesięczne zestwienie, kontrola budżetu">
	<meta name="author" content="Przemysław Kapela">
	<meta http-equiv="X-Ua-Compatible" content="IE=edge">
	
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/fontello.css">
	<link rel="stylesheet" href="main.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
	
	<!--[if lt IE 9]>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
	<![endif]-->
	
</head>

<body>

	<header>
	
		<div class="col-12 text-center bg-dark pb-2">
							
				<a href="index.php">			
					<i class="icon-dollar"></i>
					<h1>MyBudget</h1>
					<i class="icon-dollar"></i>
				</a>			
		
			<blockquote class="blockquote">
				
					<p class="mb-1">To nie pieniądze dają szczęście, ale to, co dzięki nim możesz zrobić ze swoim życiem.</p>
					
					<p class="stopa blockquote-footer text-center">Lois P. Frankel</p>
				
			</blockquote>
		</div>
	</header>
	
	<main>
		
		<section class="budget">
		
			<div class="container">
				
				<div class="row">
					
					<div class="offset-lg-4 col-lg-4 text-center mt-3 p-3 mb-2">
						
						<h2 style="font-size: 26px;"><b>Rejestracja</b></h2>
						
						<form method="post">
						
							<div class="input-group mb-3 mt-3">
							
								<div class="input-group-prepend"> <!-- pozmieniaj id=basic-addon1 oraz aria-describedby="basic-addon1-->
									<span class="input-group-text" id="basic-addon1"><i class="icon-user"></i></span>
								</div>
									<input type="text" name="username" class="form-control" placeholder="Nazwa użytkownika" aria-label="Nazwa użytkownika" aria-describedby="basic-addon1">
							</div>
							<?php
								if(isset($_SESSION['e_username']))
								{
									echo $_SESSION['e_username'];
									unset($_SESSION['e_username']);
								}		
							?>
							
							<div class="input-group mb-3">
							
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="icon-mail-alt"></i></span>
								</div>
									<input type="text" name="email" class="form-control" placeholder="Adres e-mail" aria-label="Adres e-mail" aria-describedby="basic-addon1">
							</div>
							<?php
								if(isset($_SESSION['e_email']))
								{
									echo $_SESSION['e_email'];
									unset($_SESSION['e_email']);
								}		
							?>
							<div class="input-group mb-3">
							
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="icon-lock"></i></span>
								</div>
									<input type="password" name="pass1" class="form-control" placeholder="Hasło" aria-label="Hasło" aria-describedby="basic-addon1">
							</div>
							
							<div class="input-group mb-3">
							
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="icon-lock"></i></span>
								</div>
									<input type="password" name="pass2" class="form-control" placeholder="Powtórz hasło" aria-label="Powtórz hasło" aria-describedby="basic-addon1">
							</div>
							<?php
								if(isset($_SESSION['e_pass']))
								{
									echo $_SESSION['e_pass'];
									unset($_SESSION['e_pass']);
								}		
							?>
							<button class="btn btn-primary btn-lg btn-block" type="submit"><i class="icon-right-bold"></i>Zarejestruj się</button>
							
						</form>	
						
					</div>
					
				</div>	
				
			</div>
				
		</section>
		
	</main>
	
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
	
</body>
</html>