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
        <title>SheenyMa- View Reserved Tickets</title>
    </head>
    <body>
	<table>
		<tr> <td colspan="2" style="background-color:#FE9A2E;">
		<h1>SheenyMa- View Reserved Tickets</h1>
		</td> </tr>
		
		<tr><td style="background-color:#eeeeee;">
		
		<h3>Tickets booked by: <font color="#C75646"><?php echo htmlentities($_GET["user"])."<br/>";?></font></h3>
		
        <?php
			require_once("Includes/DB.php");
			
			$customerEmail = TicketDB::getInstance()->get_customer_email($_GET["user"]);
			if (!$customerEmail)
			{
				exit("The customer with email " .$_GET["user"]. " is not found. Please check the spelling and try again");
			}
			
			$stid = TicketDB::getInstance()->get_tickets_of_customer($customerEmail);
			$row = oci_fetch_array($stid);
			if (!$row)
			{
				echo "<br>No reserved tickets.";
			}
			else
			{
        ?>
		
		<table border="black">
			<tr>
				<th>Title</th>
				<th>Format</th>
				<th>Available In</th>
				<th>Language</th>
				<th>Subtitles</th>
				<th>Cinema</th>
				<th>Date</th>
				<th>Time</th>
				<th>Hall</th>
				<th>Seat</th>
			</tr>
			<?php
				$stid = TicketDB::getInstance()->get_tickets_of_customer($customerEmail);
				while ($row = oci_fetch_array($stid))
				{
					echo "<tr><td>" . htmlentities($row["TITLE"]) . "</td>";
					
					if (htmlentities($row["DIGITAL"]) == 1)
					{
						echo "<td>Digital</td>";
					}
					else
					{
						echo "<td>Film</td>";
					}
					
					if (htmlentities($row["THREED"]) == 1)
					{
						echo "<td>3D</td>";
					}
					else
					{
						echo "<td>2D</td>";
					}
					echo "<td>" . htmlentities($row["LANGUAGE"]) . "</td>";
					echo "<td>" . htmlentities($row["SUBTITLES"]) . "</td>";
					echo "<td>" . htmlentities($row["CINEMA"]) . "</td>";
					echo "<td>" . htmlentities($row["MDATE"]) . "</td>";
					echo "<td>" . htmlentities($row["MTIME"]) . "</td>";
					echo "<td>" . htmlentities($row["HALL"]) . "</td>";
					echo "<td>" . htmlentities($row["SEAT"]) . "</td></tr>\n";
				}
			}
				oci_free_statement($stid);
			?>
		</table>
		<br>
		<br>
		<form action="MBS2.php">
			<input type="submit" value="Back to Adding Bookings"/>
		</form>
		
		</td> </tr>
			
			<tr>
			<td colspan="2" style="background-color:#FE9A2E; text-align:center;"> Copyright &#169; SheenyMa
			</td> </tr>
		</table>
    </body>
</html>