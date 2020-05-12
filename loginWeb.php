<?php

	session_start();
	
	if((isset($_SESSION['loged']))&&($_SESSION['loged']==true))
	{
		header('Location: mainMenuWeb.php');
		exit();
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
							<?php
								if(isset ($_SESSION['registered']))
								{
									echo "<p>Dziękujemy za rejestracje! Możesz już się zalogować na swoje konto!</p>";
									unset($_SESSION['registered']);
								}
							?>
						<h2 style="font-size: 26px;"><b>Logowanie</b></h2>
							
						<form action="backendLogin.php" method="post">
							<div class="input-group mb-3 mt-3">
							
								<div class="input-group-prepend"> <!-- pozmieniaj id=basic-addon1 oraz aria-describedby="basic-addon1-->
									<span class="input-group-text" id="basic-addon1"><i class="icon-user"></i></span>
								</div>
									<input type="text" name="login" class="form-control" placeholder="Nazwa użytkownika" aria-label="Nazwa użytkownika" aria-describedby="basic-addon1">
							</div>
							
							<div class="input-group mb-3">
							
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="icon-lock"></i></span>
								</div>
									<input type="password" name="pass" class="form-control" placeholder="Hasło" aria-label="Hasło" aria-describedby="basic-addon1">
							</div>
							<?php
								if(isset($_SESSION['wrong']))
									echo $_SESSION['wrong'];
								unset($_SESSION['wrong']);
							?>
							<input type="hidden" id="currentDate" name="currentDate">
							<button class="btn btn-success btn-lg btn-block mt-3" type="submit" onclick="setCurrentDate()"><i class="icon-login"></i>Zaloguj się</button>
						</form>
						
					</div>
					
					
				</div>	
				
			</div>
				
		</section>
		
	</main>
	
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
	
	<script src="memory.js"></script>
	
</body>
</html>