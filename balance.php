<?php
	
	session_start();
	
	if(!isset($_SESSION['loged']))
	{
		header('Location: loginWeb.php');
		exit();
	}
	else
	{
		$date1 = $_SESSION['currentDate'];
		
		if(isset($_SESSION['datka']))
		{
		$date1 = $_SESSION['datka'];
		unset($_SESSION['datka']);
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
					
					if (isset ($_SESSION['datka2']))
							{
								$date2 = $_SESSION['datka2'];
								$expense_query = "SELECT * FROM expenses WHERE user_id=$user_id AND date_of_expense BETWEEN '$date1' AND '$date2' ORDER BY expense_category_assigned_to_user_id, date_of_expense";
							}
							else
								$expense_query = "SELECT * FROM expenses WHERE user_id=$user_id AND date_of_expense LIKE '$date1%' ORDER BY expense_category_assigned_to_user_id, date_of_expense";
							
					if($result = $connect->query($expense_query))
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
			
			<div class="offset-lg-3 col-lg-8 offset-lg-3 collapse navbar-collapse" id="navbarNav">
			
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
					<li class="nav-item active" style="background-color: white;">
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
				
					
					
					<div class="input-group offset-xl-9 col-xl-3 mt-4" >
						<div class="dropdown">
							 <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?php
								$textDate = "Bieżący mieisąc";
								if(isset ($_SESSION['textDate']))
								{
									$textDate = $_SESSION['textDate'];
									unset($_SESSION['textDate']);
								}
								echo $textDate;
							?>
							 </button>

							 <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
								<button class="dropdown-item" name="current_Month" id="current_Month" value="Bieżący miesiąc" type="button">Bieżący miesiąc</button>
								<button class="dropdown-item" name="prevoius_Month" id="prevoius_Month" value="Poprzedni miesiąc" type="button">Poprzedni miesiąc</button>
								<button class="dropdown-item" name="current_Year" id="current_Year" value="Bieżący rok" type="button">Bieżący rok</button>

								<button class="dropdown-item" type="button" id="uncommon_Text_Date" value="Niestandardowy" data-toggle="modal" data-target="#exampleModalCenter">Niestandardowy</button>
							 </div>
						</div>
					</div>
					
					<!-- Modal \/-->
					<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
					  <div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
						  <div class="modal-header" style="display:block">
							<button type="button" class="close" style="float: right;" data-dismiss="modal" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
							</button>
							<h3 class="modal-title" style="text-align: center">Zakres dat do bilansu</h3>
						  </div>
						 <h3 style="font-size: 20px; text-align: center;" class="mt-3 mb-0">Podaj datę początkową</h3>
						  
						<form action="balanceDate.php" method="post">
							 <div class="modal-body">
							  
								<div class="input-group mb-3">
								
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="icon-calendar"></i></span>
									</div>
										<input type="date" class="form-control" id="datePicker" name="datePicker" aria-label="Data" aria-describedby="basic-addon1">
								</div>
								<h3 style="font-size: 20px; text-align: center;" class="mt-3 mb-3">Podaj datę końcową</h3>
								
								<div class="input-group mb-3">
								
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="icon-calendar"></i></span>
									</div>
										<input type="date" class="form-control" id="datePicker2" name="datePicker2" aria-label="Data" aria-describedby="basic-addon1">
								</div>
							 </div>
					
						  
						  <div class="modal-footer"style="display: block;">
						  <div class="col-lg-5 p-0 mb-3" style="float: left; margin-left: 0px;">
							
								<button class="btn btn-success btn-lg btn-block" name="uncommonDate" id="uncommonDate" type="submit" data-dismiss="modal" style="font-size: 15px;">Wybierz zakres dat</button>
				
							</div>
							
							<div class="col-lg-5 p-0 mb-3" style="float: right;">
									
								<button class="btn btn-danger btn-lg btn-block" type="button" data-dismiss="modal" style="font-size: 15px;">Zamknij</button>
									
							</div>
						  </div>
						</form>
						</div>
					  </div>
					</div>
					<!-- Modal /\-->
					
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
							if (isset ($_SESSION['datka2']))
							{
								$date2 = $_SESSION['datka2'];
								$income_query = "SELECT * FROM incomes WHERE user_id=$user_id AND date_of_income BETWEEN '$date1' AND '$date2' ORDER BY income_category_assigned_to_user_id, date_of_income";
							}
							else
								$income_query = "SELECT * FROM incomes WHERE user_id=$user_id AND date_of_income LIKE '$date1%' ORDER BY income_category_assigned_to_user_id, date_of_income";
							
							if($result = $connect->query($income_query))
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
							
							if (isset ($_SESSION['datka2']))
							{
								$date2 = $_SESSION['datka2'];
								unset($_SESSION['datka2']);
								$expense_query = "SELECT * FROM expenses WHERE user_id=$user_id AND date_of_expense BETWEEN '$date1' AND '$date2' ORDER BY expense_category_assigned_to_user_id, date_of_expense";
							}
							else
								$expense_query = "SELECT * FROM expenses WHERE user_id=$user_id AND date_of_expense LIKE '$date1%' ORDER BY expense_category_assigned_to_user_id, date_of_expense";
							
							if($result = $connect->query($expense_query))
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
						if(isset($income_sum))
						{
							$balance = $income_sum - $expense_sum;
							echo "<div>$income_sum - $expense_sum = $balance</div>";
							if($balance<0)
							{
								echo "<div> Twój bilans finansowy jest ujemny, uważaj, popadasz w długi!</div>";
							}
							else
								echo "<div> Gratulacje! Twój bilans finansowy jest dodatni! Zaoszczędziłeś $balance.</div>";
								//echo $date1;
								//echo $date2;
						}
						else
							echo "<div> Brak danych!</div>";
						?>
					</div>
					
					<div id="donutchart" style=" height: 500px;" class="col-lg-12 mt-3 mb-2"></div>

				</div>	
				
			</div>
				
		</section>
		
	</main>
	
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
	
	<script src="memory.js"></script>
	
</body>
</html>

<script>
var today = new Date();
var year = today.getFullYear();

$(document).ready(function(){
	$("#prevoius_Month").click(function(){ //id
		var month = today.getMonth();

		if(month<10)
		{
		  month="0"+month;
		}
		var datka = year+"-"+month;
		//alert (datka);
		var textDate = $('#prevoius_Month').val(); //name
		
			$.ajax({
			url: "balanceDate.php",
			method: "POST",
			data: {datka:datka, textDate:textDate}, //js:php
				success:function()
				{
					location.reload();
				}		 
		});
	});
});
$(document).ready(function(){
	$("#current_Month").click(function(){
		var month = today.getMonth()+1;
		if(month<10)
		{
		  month="0"+month;
		}
		var datka = year+"-"+month;
		//alert (datka);
		var textDate = $('#current_Month').val(); 
					 
			$.ajax({
			url: "balanceDate.php",
			method: "POST",
			data: {datka:datka, textDate:textDate}, //js:php
				success:function()
				{
					location.reload();
				}		 
		});
	});
});
$(document).ready(function(){
	$("#current_Year").click(function(){
		var datka = year;
		//alert (datka);
		var textDate = $('#current_Year').val();
					 
			$.ajax({
			url: "balanceDate.php",
			method: "POST",
			data: {datka:datka, textDate:textDate}, //js:php
				success:function()
				{
					location.reload();
				}		 
		});
	});
});
$(document).ready(function(){
	$("#uncommonDate").click(function(){
		var datka = $('#datePicker').val();
		var datka2 = $('#datePicker2').val();
		//alert (datka);
		//alert (datka2);
		var textDate = $('#uncommon_Text_Date').val();
		//alert (textDate);			 
			$.ajax({
			url: "balanceDate.php",
			method: "POST",
			data: {datka:datka, datka2:datka2, textDate:textDate}, //js:php
				success:function()
				{
					location.reload();
				}		 
		});
	});
});
</script>