<?php
	session_start();
	if (!array_key_exists("user", $_SESSION))
	{
		header('Location: Index.php');
		exit;
	}
	
	require_once("Includes/DB.php");
	
	if ($_SERVER['REQUEST_METHOD'] == "POST")
	{
		if (!empty($_POST['deleteList']))
		{
			$count = 0;
			foreach($_POST['deleteList'] as $ticketID)
			{
				TicketDB::getInstance()->delete_booking($ticketID);
				$count++;
			}
		}
		
		echo "<p align=\"center\">You have deleted ".$count." bookings.<br></p>";
	}
?>
<html>
	<head>
		<title>EditBooking.php</title>
	</head>
	<body>
		<form name="editBooking" action="EditBooking.php" method="POST">
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
				<th>Remove Booking?</th>
			</tr>
			<?php
				require_once("Includes/DB.php");
				$customerEmail = TicketDB::getInstance()->get_customer_email($_SESSION["user"]);
				$stid = TicketDB::getInstance()->get_tickets_of_customer($customerEmail);
				
				while ($row = oci_fetch_array($stid)):
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
					echo "<td>" . htmlentities($row["SEAT"]) . "</td>";
					echo "<td align=\"center\"><input type=\"checkbox\" name=\"deleteList[]\" value=\"".$row["TICKETID"]."\"></td>";
					echo "</tr>\n";
				endwhile;
				oci_free_statement($stid);
			?>
			
		</table>
		  
            <input type="submit" name="makeChanges" value="Make Changes">
        </form>
		
		<form action="AdminSelectCustomer.php">
			<input type="submit" value="Back To Main Page"/>
		</form>
	</body>
</html>