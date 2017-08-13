<?php
	session_start();
	if (!array_key_exists("user", $_SESSION))
	{
		header('Location: adminIndex.php');
		exit;
	}
?>


<html>
	<head>
		<title>SheenyMa Movie Ticket Admin System</title>
	</head>
	<body>
		<table>
			<tr> <td colspan="2" style="background-color:#FE9A2E;">
			<h1>SheenyMa Movie Ticket System</h1>
			</td> </tr>
			
			<tr> <td style="background-color:#eeeeee;">
			
			<table>
				<tr>
				<td><a href="MBS2.php"> Add Booking</a><td> <td></td>
				</tr>
				<tr>
				<td><a href="EditBooking.php"> Delete Booking</a></td> <td></td>
				<td></td>
				<td>
				<form action="Logout.php">
					<input type="submit" value="Logout">
				</form>
			</td>
				</tr>
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
			<td colspan="2" style="background-color:#FE9A2E; text-align:center;"> Copyright &#169; SheenyMa
			</td> </tr>
		</table>
	</body>
</html>