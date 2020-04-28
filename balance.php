<?php
	
	session_start();
	
	if(!isset($_SESSION['loged']))
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
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['Work',     11],
          ['Eat',      2],
          ['Commute',  2],
          ['Watch TV', 2],
          ['Sleep',    7]
        ]);

        var options = {
          title: 'My Daily Activities',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
    </script>
</head>

<body>

	<header>
	
		<div class="col-12 text-center bg-dark pb-2">
							
				<i class="icon-dollar"></i>
				<h1>MyBudget</h1>
				<i class="icon-dollar"></i>
				
		
			<blockquote class="blockquote">
				
					<p class="mb-1">To nie pieniądze dają szczęście, ale to, co dzięki nim możesz zrobić ze swoim życiem.</p>
					
					<p class="stopa blockquote-footer text-center">Lois P. Frankel</p>
				
			</blockquote>
		</div>
		
		<nav class="navbar navbar-expand-xl navbar-light">
		  
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			
			<div class="lol col-lg-8 collapse navbar-collapse" id="navbarNav">
			
				<ul class="navbar-nav">
				
					<li class="nav-item">
						<a class="nav-link" href="mainMenuWeb.php"><i class="icon-home"></i>Strona główna</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="addIncomeWeb.php"><i class="icon-money"></i>Dodaj przychód</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="addExpenceWeb.php"><i class="icon-basket"></i>Dodaj wydatek</a>
					</li>
					<li class="nav-item active">
						<a class="nav-link" href="balance.php"><i class="icon-chart-bar"></i>Przeglądaj bilans</a>
					</li>
					
					<li class="nav-item dropdown " >
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="icon-calendar"></i>Wybierz okres
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
							<a class="dropdown-item" href="#">Bieżący miesiąc</a>
							<a class="dropdown-item" href="#">Poprzedni miesiąc</a>
							<a class="dropdown-item" href="#">Ostatni rok</a>
							<a class="dropdown-item" href="#">Niestandardowy</a>
						</div>
					 </li>
					 
					 <li class="nav-item">
						<a class="nav-link" href="#"><i class="icon-cog"></i>Ustawienia</a>
					</li>
					
					 <li class="nav-item">
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
				
					<h2 class="lol col-12 mt-4 text-center"><b>Bieżący miesiąc</b></h2>
					
					<div class="input-group offset-xl-9 col-xl-3">
							 
						<select id="1" name="date" class="form-control"  aria-label="Text input with dropdown button">
		
							<option value="w">Bieżący miesiąc</option>
							<option value="o">Poprzedni miesiąc</option>
							<option value="s">Ostatni rok</option>
							<option value="r">Niestandardowy</option>
						
						</select>

					</div>
					
					<div class="balance   col-lg-6 text-center mt-3 mb-2">
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
							
							
							if($result = $connect->query("SELECT date_of_income, amount, income_comment FROM incomes WHERE user_id=$user_id"))
							
							{
								$user_number = $result->num_rows;
								if($user_number >0)
								{
									while ($row = $result->fetch_assoc()) //tworzymy tablice line z wartosciami z bazy sql, które zwroci nam kwerenda $sql
									{
										echo "<div>data: ".$row["date_of_income"]." - kwota: ".$row["amount"]." - komentarz: ".$row["income_comment"]."</div>";
									}
									$result->close();
								}
								else 
									echo "0 rezulataów";
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
						
					</div>
					
					<div class="balance  col-lg-6 text-center mt-3  mb-2">
					Wydatki
						
					</div>
					
					<div class="balance   col-lg-12 text-center mt-3 mb-2">
					Bilans
						
					</div>
					
					<div id="donutchart" style="width: 900px; height: 500px;"></div>

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