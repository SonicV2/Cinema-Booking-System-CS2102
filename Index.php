<?php
	require_once("Includes/DB.php");
	$logonSuccess = false;


	// verify user's credentials
	if ($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$logonSuccess = (TicketDB::getInstance()->verify_customer_credentials($_POST['user'], $_POST['userpassword']));
		if ($logonSuccess == true)
		{
			session_start();
			$_SESSION['user'] = $_POST['user'];
			header('Location: MBS.php');
			exit;
		}
	}
?>

<html>
	<head>
		<title>SheenyMa Movie Ticket System- Login</title>
	</head>
	<body>
		<table>
			<tr> <td colspan="2" style="background-color:#72B3CC;">
			<h1>SheenyMa Movie Ticket System</h1>
			</td> </tr>
			
			<tr> <td style="background-color:#eeeeee;">
			<h3>Login</h3>
			
			<table>
			<form name="logon" action="Index.php" method="POST">
				<tr>
				<td>Email:</td> <td><input type="text" name="user"></td>
				</tr>
				<tr>
				<td>Password:</td> <td><input type="password" name="userpassword"></td>
				<td><input type="submit" value="Login"></td>
				</tr>
			</form>
			</table>
			<?php
				  if ($_SERVER["REQUEST_METHOD"] == "POST")
				  { 
					  if (!$logonSuccess)
						  echo "<p style=\"color:#C75646\">Invalid email and/or password.</p>";
				  }
			?>
			Don't have an account? <a href="RegisterCustomer.php">Click Here</a>
		
			</td> </tr>
			
			<tr>
			<td colspan="2" style="background-color:#72B3CC; text-align:center;"> Copyright &#169; SheenyMa
			</td> </tr>
		</table>
	</body>
</html>