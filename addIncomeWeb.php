<?php
	
	session_start();
	
	if(isset($_SESSION['loged']))
	{
		if(isset($_POST['money']))
		{
			//ustawienie flagi udanej walidacji
			$all_OK = true;
			//poprawnosc money
			$money = $_POST['money'];
			if($money == "") 
			{
				$all_OK = false;
				$_SESSION['e_money'] = '<div class="input-group mb-3" style= "color: red">Nie podano żadnej kwoty!</div>';
			}
			
			//poprawnosc daty
			
			$date = $_POST['date'];
			$sql_date = str_replace("-","", $date);

			// poprawnosc rodzaju przychodu
			$income = $_POST['income'];
			
			if($income == 0)
			{
				$all_OK = false;
				$_SESSION['e_income'] = '<div class="input-group mb-3" style= "color: red">Nie wybrano formy przychodu!</div>';
			}
			// poprawnosc komentarza
			$comment = $_POST['comment'];
		
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
					if($all_OK == true)
					{
				
						// walidacja udana
						$user_id = $_SESSION['id'];
						if($connect->query("INSERT INTO incomes VALUES (NULL, $user_id,$income,$money, $sql_date, '$comment')"))
						{
							$_SESSION['added_income'] = '<p style="color: green">Dodano przychód! Chcesz wprowadzić następny?</p>';
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
				//echo $e;
			}
		}
	}
	else
	{
		header('Location: loginWeb.php');
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
							
				<a href="mainMenuWeb.php">			
					<i class="icon-dollar"></i>
					<h1>MyBudget</h1>
					<i class="icon-dollar"></i>
				</a>
				
		
			<blockquote class="blockquote">
				
					<p class="mb-1">To nie pieniądze dają szczęście, ale to, co dzięki nim możesz zrobić ze swoim życiem.</p>
					
					<p class="stopa blockquote-footer text-center">Lois P. Frankel</p>
				
			</blockquote>
		</div>
		
		<nav class="navbar navbar-expand-xl navbar-light">
		  
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			
			<div class="offset-lg-3 col-lg-8 offset-lg-3 collapse navbar-collapse" id="navbarNav">
			
				<ul class="navbar-nav">
				
					<li class="nav-item">
						<a class="nav-link" href="mainMenuWeb.php"><i class="icon-home"></i>Strona główna</a>
					</li>
					<li class="nav-item active" style="background-color: white;">
						<a class="nav-link" href="addIncomeWeb.php"><i class="icon-money"></i>Dodaj przychód</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="addExpenceWeb.php"><i class="icon-basket"></i>Dodaj wydatek</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="balance.php"><i class="icon-chart-bar"></i>Przeglądaj bilans</a>
					</li>

					 <li class="nav-item">
						<a class="nav-link" href="#"><i class="icon-cog"></i>Ustawienia</a>
					</li>
					
					<li class="nav-item ">
						<a class="nav-link" href="logout.php"><i class="icon-off"></i>Wyloguj</a>
					</li>
				</ul>
			</div>
		</nav>
		
	</header>
	
	<main>
		
		<section class="budget">
		
			<div class="container">
				
				<div class="row">
					
					<div class="offset-lg-4 col-lg-4 text-center mt-3 p-3 mb-2">
						
							<?php
								if(isset ($_SESSION['added_income']))
								{
									echo $_SESSION['added_income'];
									unset($_SESSION['added_income']);
								}
							?>
					
						<h2><b>Wprowadź dane przychodu:</b></h2>
						
						<form method="post">
						
							<div class="input-group mb-3 mt-3">
							
								<div class="input-group-prepend"> <!-- pozmieniaj id=basic-addon1 oraz aria-describedby="basic-addon1-->
									<span class="input-group-text" id="basic-addon1"><i class="icon-money-1"></i></span>
								</div>
									<input type="number" name="money" class="form-control" step="0.01" placeholder="Kwota" aria-label="Kwota" aria-describedby="basic-addon1">
							</div>
							<?php
								if(isset($_SESSION['e_money']))
								{
									echo $_SESSION['e_money'];
									unset($_SESSION['e_money']);
								}		
							?>
							<div class="input-group mb-3">
							
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="icon-calendar"></i></span>
								</div>
									<input type="date" name="date" class="form-control" id="datePicker" name="datePicker" aria-label="Data" aria-describedby="basic-addon1">
							</div>
							
							<div class="input-group mb-3">
								 
									<select id="1" name="income" class="form-control"  aria-label="Text input with dropdown button">
										<?php
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
												$user_id = $_SESSION['id'];
												if($result = $connect->query("SELECT * FROM incomes_category_assigned_to_users WHERE user_id=$user_id"))
												{
													while ($row = $result->fetch_assoc()) 
													{
														$cate_id = $row["id"];
														$name = $row["name"];
														echo '<option value="'.$cate_id.'">'.$name.'</option>';
													}
													$result->close();	
												}	
												else 		
													throw new Exception ($connect->error);	
												
												$connect->close();
											}
										}
										catch(Exception $e)
										{
											echo '<div style= "color: red">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</div>';
											//echo $e;
										}
										?>
										<option value="0" selected>--Wybierz rodzaj przychodu--</option>
									</select>

							</div>
							<?php
								if(isset($_SESSION['e_income']))
								{
									echo $_SESSION['e_income'];
									unset($_SESSION['e_income']);
								}		
							?>
							<div class="input-group mb-4">
							
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="icon-pencil-alt"></i></span>
								</div>
									<input type="text" name="comment" class="form-control" placeholder="Komentarz" aria-label="Komantarz" aria-describedby="basic-addon1">
							</div>
							
							<div class="col-lg-5 p-0" style="float: left; margin-left: 0px;">
							
								<button class="btn btn-success btn-lg btn-block" type="submit">Dodaj</button>
				
							</div>
							
							<div class="col-lg-5 p-0" style="float: right;">
									
								<button class="btn btn-danger btn-lg btn-block" type="reset">Anuluj</button>
									
							</div>
							
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