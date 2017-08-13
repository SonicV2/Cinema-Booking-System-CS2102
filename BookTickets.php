<?php
	session_start();
	if (!array_key_exists("user", $_SESSION))
	{
		header('Location: Index.php');
		exit;
	}
?>

<html>
<head> <title>SheenyMa- Find Seats</title> </head>

<body>
<table>
<tr> <td colspan="2" style="background-color:#72B3CC;">
<h1>SheenyMa- Find Seats</h1>
</td> </tr>

<?php
	putenv('ORACLE_HOME=/oraclient');
	$dbh = ocilogon('A0094762', 'crse1410', sid3);
?>

<tr>
<td style="background-color:#eeeeee;" width="60%">
<?php
	if (isset($_GET['bookTickets']))
	{
		if (!empty($_GET['seatList']))
		{
			$count = 0;
			foreach($_GET['seatList'] as $ticketID)
			{
				$sql = "UPDATE MovieTicket
						SET email = '".$_GET['userEmail']."'
						WHERE ticketID = '".$ticketID."'";
				$stid = oci_parse($dbh, $sql);
				oci_execute($stid, OCI_COMMIT_ON_SUCCESS);
				oci_free_statement($stid);
				$count++;
			}
			
			echo "<p align=\"center\">You have successfully bought ".$count." tickets.<br>
				  Thank you for purchasing.<br><br>";
		}
		else
		{
			echo "<p align=\"center\">You did not select any seats.<br><br>";
		}
		echo "<button onClick=\"window.close()\">Close Window</button>";
		echo "</p>";
	}
	else
	{
?>
<h2>Movie: 
<?php
	$sql = "SELECT DISTINCT M.title, M.actors, M.runtime, M.director, M.parating, M.synopsis
			FROM Movie M
			WHERE M.movieID = '".$_GET['movieID']."'";
	$stid = oci_parse($dbh, $sql);
	oci_execute($stid, OCI_DEFAULT);
	$movie = oci_fetch_array($stid);
	oci_free_statement($stid);
	
	$sql = "SELECT DISTINCT V.language, V.subtitles, V.digital, V.threeD
			FROM Version V
			WHERE V.movieID = '".$_GET['movieID']."' AND
				  V.versionID = '".$_GET['versionID']."'";
	$stid = oci_parse($dbh, $sql);
	oci_execute($stid, OCI_DEFAULT);
	$version = oci_fetch_array($stid);
	oci_free_statement($stid);
	
	echo "".$movie[0]."</h2>";
	echo "<table width=\"100%\">
		  <col width=\"10%\">
		  <col width=\"40%\">
		  <col width=\"12%\">
		  <col width=\"38%\">
		  <tr><td><b>Details</b></td></tr>";
		  
	echo "<tr>";
	echo "<td>Actors:</td><td>".$movie[1]."</td>";
	echo "<td>Run Time:</td><td>".$movie[2]."</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td>Director:</td><td>".$movie[3]."</td>";
	echo "<td>Parental Rating:</td><td>".$movie[4]."</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td>Language:</td><td>".$version[0]."</td>";
	echo "<td>Subtitle:</td><td>".$version[1]."</td>";
	echo "</tr>";
	
	echo "<tr>";
	if ($version[2] == 1)	// if movie version is digital
	{
		echo "<td>Format:</td><td>Digital</td>";
	}
	else
	{
		echo "<td>Format:</td><td>Film</td>";
	}
	if ($version[3] == 1)	// if movie version is 3D
	{
		echo "<td>3D:</td><td>Yes</td>";
	}
	else
	{
		echo "<td>3D:</td><td>NIL</td>";
	}
	echo "</tr>";
	echo "</table>";
	
	echo "<br>";
	echo "<table>
		  <tr><td><b>Synopsis</b></td></tr>
		  <tr><td>".$movie[5]."</td></tr>
		  </table>";
		  
	$sql = "SELECT DISTINCT MT.cinema, MT.mDate, MT.hall, MT.mTime
			FROM MovieTicket MT
			WHERE MT.movieID = '".$_GET['movieID']."' AND
				  MT.versionID = '".$_GET['versionID']."' AND
				  MT.cinema = '".$_GET['cinema']."' AND
				  MT.mDate = '".$_GET['mDate']."' AND
				  MT.mTime = '".$_GET['mTime']."'";
	$stid = oci_parse($dbh, $sql);
	oci_execute($stid, OCI_DEFAULT);
	$ticketInfo = oci_fetch_array($stid);
	oci_free_statement($stid);
?>

<br>
<table width="100%">
<col width="10%">
<col width="40%">
<col width="12%">
<col width="38%">
<tr><td><b>Ticket Info</b></td></tr>

<tr>
<td>Cinema:</td><td><?=$ticketInfo[0]?></td>
<td>Date:</td><td><?=$ticketInfo[1]?></td>
</tr>

<tr>
<td>Hall:</td><td><?=$ticketInfo[2]?></td>
<td>Time:</td><td><?=$ticketInfo[3]?></td>
</tr>
</table><br>

<table>
<tr>
<td>
<img src="cinema_seat.gif" alt="Seat Layout" style="width:571px;height:331px">
</td>
</tr>

<tr>
<td>
Select Seats:
</td>
</tr>

<tr>
<td>
<form>
<table border="1"  align="center">
<?php
	$sql = "SELECT MT.ticketID, MT.email, MT.seat
			FROM MovieTicket MT
			WHERE MT.movieID = '".$_GET['movieID']."' AND
				  MT.versionID = '".$_GET['versionID']."' AND
				  MT.cinema = '".$_GET['cinema']."' AND
				  MT.mDate = '".$_GET['mDate']."' AND
				  MT.mTime = '".$_GET['mTime']."'
			ORDER BY MT.ticketID ASC";
	$stid = oci_parse($dbh, $sql);
	oci_execute($stid, OCI_DEFAULT);
	
	for($x='A'; $x<='D'; $x++)
	{
		echo "<tr>";
		for($i=1; $i<=8; $i++)
		{
			if ($i == 3 || $i == 7)
			{
				echo "<td><pre>     </pre></td>";
			}
			
			$ticket = oci_fetch_array($stid);
			if (is_null($ticket[1]))
			{
				echo "<td><input type=\"checkbox\" name=\"seatList[]\" value=\"".$ticket[0]."\">".$ticket[2]."</td>";
			}
			else
			{
				echo "<td><input type=\"checkbox\" name=\"seatList[]\" value=\"".$ticket[0]."\" DISABLED><font color=\"grey\">".$ticket[2]."</font></td>";
				//echo "<td>Occupied</td>";
			}
		}
		echo "</tr>";
	}
	oci_free_statement($stid);
?>
</table>
<input type="hidden" name="userEmail" value="<?=$_GET['email']?>">
<p style="font-size:small" align="center">*Greyed-out entries: Seat Occupied</p>
<p align="right"><input type="submit" name="bookTickets" value="Book Tickets">
				 <button onClick="window.close()">Close Window</button></p>
</form>
</td>
</tr>

</table>

</td> </tr>

<?php
	}
	oci_close($dbh);
?>

<tr>
<td colspan="2" style="background-color:#72B3CC; text-align:center;"> Copyright &#169; SheenyMa
</td> </tr>
</table>

</body>
</html>