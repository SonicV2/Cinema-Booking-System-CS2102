<?php
	require_once("Includes/DB.php");

	/** other variables */
	$userNameIsUnique = true;
	$passwordIsValid = true;				
	$userIsEmpty = false;					
	$passwordIsEmpty = false;				
	$password2IsEmpty = false;
	$creditCardNumIsEmpty = false;
	
	/** Check that the page was requested from itself via the POST method. */
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		/** Check whether the user has filled in the user's email in the text field "user" */    
		if ($_POST["user"]=="")
		{
			$userIsEmpty = true;
		}
		
		$customerEmail = TicketDB::getInstance()->get_customer_email($_POST["user"]);
		if ($customerEmail)
		{
		   $userNameIsUnique = false;
		}
		
		/** Check whether a password was entered and confirmed correctly */
		if ($_POST["password"]=="")
		{
			$passwordIsEmpty = true;
		}
		if ($_POST["password2"]=="")
		{
			$password2IsEmpty = true;
		}
		if ($_POST["password"]!=$_POST["password2"])
		{
			$passwordIsValid = false;
		}
		
		/** Check whether user has entered a credit card number */
		if ($_POST["ccnum"]=="")
		{
			$creditCardNumIsEmpty = true;
		}
		
		/** Check whether the boolean values show that the input data was validated successfully.
		 * If the data was validated successfully, add it as a new entry in the "Customer" database.
		 * After adding the new entry, close the connection and redirect the application to EditBooking.php.
		 */
		if (!$userIsEmpty && $userNameIsUnique && !$passwordIsEmpty &&
			!$password2IsEmpty && $passwordIsValid && !$creditCardNumIsEmpty)
		{
			TicketDB::getInstance()->create_customer($_POST["user"], $_POST["password"], $_POST["ccnum"]);
			session_start();
			$_SESSION['user'] = $_POST['user'];
			header('Location: MBS.php');
			exit;
		}
	}
?>

<html>
	<head>
		<title>SheenyMa- Register An Account</title>
	</head>
	<body>
		<table>
		<tr> <td colspan="2" style="background-color:#72B3CC;">
		<h1>SheenyMa- Register An Account</h1>
		</td> </tr>
		<tr><td style="background-color:#eeeeee;" width="60%">
		
		<h3>Welcome!</h3>
		
		<table>
        <form action="RegisterCustomer.php" method="POST">
			<tr>
            <td>Your email:</td> <td><input type="text" name="user"/><br/></td>
			<td><?php
				if ($userIsEmpty)
				{
					echo ("<font color=\"#C75646\">*Enter your email, please!</font>");
					echo ("<br/>");
				}                
				if (!$userNameIsUnique)
				{
					echo ("<font color=\"#C75646\">*The email already exists. Please enter another email and try again.</font>");
					echo ("<br/>");
				}
			?></td>
			</tr>
			
			<tr>
            <td>Password:</td> <td><input type="password" name="password"/><br/></td>
			<td><?php
				if ($passwordIsEmpty)
				{
					echo ("<font color=\"#C75646\">*Enter your password, please!</font>");
					echo ("<br/>");
				}             
			?></td>
			</tr>
			
			<tr>
            <td>Confirm password:</td> <td><input type="password" name="password2"/><br/></td>
			<td><?php
				if ($password2IsEmpty)
				{
					echo ("<font color=\"#C75646\">*Confirm your password, please!</font>");
					echo ("<br/>");    
				}                
				if (!$password2IsEmpty && !$passwordIsValid)
				{
					echo  ("<font color=\"#C75646\">*The passwords do not match!</font>");
					echo ("<br/>");  
				}                 
			?></td>
			</tr>
			
			<tr>
			<td>Credit Card Number:</td> <td><input type="text" name="ccnum"/><br/></td>
			<td><?php
				if ($creditCardNumIsEmpty)
				{
					echo ("<font color=\"#C75646\">*Enter your credit card number, please!</font>");
					echo ("<br/>");
				}             
			?></td>
			</tr>
			<tr>
            <td><input type="submit" value="Register"/></td>
			</tr>
		</form>
		</table>
		
		<br>
		<form name="backToMainPage" action="Index.php">
			<input type="submit" value="Back To Login Page"/>
		</form>
		
		</td> </tr>
		<tr>
		<td colspan="2" style="background-color:#72B3CC; text-align:center;"> Copyright &#169; SheenyMa
		</td> </tr>
		</table>
	</body>
</html>