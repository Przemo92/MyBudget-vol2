<?php
	
	session_start();
	
	if(!isset($_SESSION['loged']))
	{
		header('Location: loginWeb.php');
		exit();
	}
	else
	{
		if(isset($_POST['balanceDate']))
		{
			$date = $_POST['balanceDate'];
			$trueDate = str_replace("-","", $date);
			echo "$trueDate";
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
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Expense_category', 'cost'],				
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

					if($result = $connect->query("SELECT * FROM expenses WHERE user_id=$user_id ORDER BY expense_category_assigned_to_user_id"))
					{
						$user_number = $result->num_rows;
						if($user_number >0)
						{
							$temp_cat =0;
							$expense_sum=0;
							while ($row = $result->fetch_assoc()) //tworzymy tablice line z wartosciami z bazy sql, które zwroci nam kwerenda $sql
							{
								if($temp_cat!=$row["expense_category_assigned_to_user_id"])
								{
									$cat_id = $row["expense_category_assigned_to_user_id"];
									$result2 = $connect->query("
									SELECT 
									SUM(expenses.amount) AS sum, expenses_category_assigned_to_users.name 
									FROM 
									expenses_category_assigned_to_users, expenses 
									WHERE 
									expenses_category_assigned_to_users.user_id = $user_id AND expenses_category_assigned_to_users.user_id = expenses.user_id AND expenses_category_assigned_to_users.id = $cat_id AND expenses_category_assigned_to_users.id = expenses.expense_category_assigned_to_user_id");
									$row2 = $result2->fetch_assoc();
									$name = $row2["name"];
									$expense_sum = $row2['sum'];
									$result2->close();
									echo "['$name',     $expense_sum],";
								}
								$temp_cat =$row["expense_category_assigned_to_user_id"];
							}
							$result->close();
						}
						else 
							echo "['brak danych',     1],";
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
        ]);

        var options = {
          title: 'Wydatki w wybranym okresie czasowym',
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
						<div class="dropdown">
							 <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Bieżący miesiąc
							 </button>
							 <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
								<button class="dropdown-item" onclick="currentMonth()" type="button">Bieżący miesiąc</button>
								<button class="dropdown-item" onclick="previousMonth()" type="button">Poprzedni miesiąc</button>
								<button class="dropdown-item" onclick="lastYear()" type="button">Ostatni rok</button>
								<button class="dropdown-item" onclick="uncommonDate()" type="button">Niestandardowy</button>
							 </div>
						</div>

					</div>

					<div class="balance   col-lg-6 text-center mt-3 mb-2">
					<h2>PRZYCHODY</h2>
					<?php
					
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

							if($result = $connect->query("SELECT * FROM incomes WHERE user_id=$user_id ORDER BY income_category_assigned_to_user_id, date_of_income"))
							
							{
								$user_number = $result->num_rows;
								if($user_number >0)
								{
									$temp_cat =0;
									$income_sum =0;
									while ($row = $result->fetch_assoc()) //tworzymy tablice line z wartosciami z bazy sql, które zwroci nam kwerenda $sql
									{
										if($temp_cat!=$row["income_category_assigned_to_user_id"])
										{
											$cat_id = $row["income_category_assigned_to_user_id"];
											$result2 = $connect->query("SELECT name FROM incomes_category_assigned_to_users WHERE user_id=$user_id AND id=$cat_id");
											$row2 = $result2->fetch_assoc();
											$name = $row2["name"];
											$result2->close();
											echo "<div>$name</div>";
										}
										echo "<div>data: ".$row["date_of_income"]." - kwota: ".$row["amount"]." - komentarz: ".$row["income_comment"]."</div>";
										$temp_cat =$row["income_category_assigned_to_user_id"];
										$income_sum = $income_sum + $row["amount"];
										
									}
									$result->close();
								}
								else 
									echo "0 rezultatów";
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
					<h2>WYDATKI</h2>
					<?php
					
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
							
							
							if($result = $connect->query("SELECT * FROM expenses WHERE user_id=$user_id ORDER BY expense_category_assigned_to_user_id, date_of_expense"))
							
							{
								$user_number = $result->num_rows;
								if($user_number >0)
								{
									$temp_cat =0;
									$expense_sum=0;
									while ($row = $result->fetch_assoc()) //tworzymy tablice line z wartosciami z bazy sql, które zwroci nam kwerenda $sql
									{
										if($temp_cat!=$row["expense_category_assigned_to_user_id"])
										{
											$cat_id = $row["expense_category_assigned_to_user_id"];
											$result2 = $connect->query("SELECT name FROM expenses_category_assigned_to_users WHERE user_id=$user_id AND id=$cat_id");
											$row2 = $result2->fetch_assoc();
											$name = $row2["name"];
											$result2->close();
											echo "<div>$name</div>";
										}
										echo "<div>data: ".$row["date_of_expense"]." - kwota: ".$row["amount"]." - komentarz: ".$row["expense_comment"]."</div>";
										$temp_cat =$row["expense_category_assigned_to_user_id"];
										$expense_sum = $expense_sum + $row["amount"];
									}
									$result->close();
								}
								else 
									echo "0 rezultatów";
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
					
					<div class="balance   col-lg-12 text-center mt-3 mb-2">
					<h2>BILANS</h2>
					Przychody - Wydatki = Dochód
						<?php
						$balance = $income_sum - $expense_sum;
						echo "<div>$income_sum - $expense_sum = $balance</div>";
						if($balance<0)
						{
							echo "<div> Twój bilans finansowy jest ujemny, uważaj, popadasz w długi!</div>";
						}
						else
							echo "<div> Gratulacje! Twój bilans finansowy jest dodatni! Zaoszczędziłeś $balance.</div>";
							echo "$trueDate";
						?>
					</div>
					
					<div id="donutchart" style=" height: 500px;" class="col-lg-12 text-center mt-3 mb-2"></div>

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