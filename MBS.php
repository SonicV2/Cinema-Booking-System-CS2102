<?php
	session_start();
	if (!array_key_exists("user", $_SESSION))
	{
		header('Location: Index.php');
		exit;
	}
?>

<html>
<head> <title>SheenyMa Movie Ticket System</title> </head>

<body>
<table>
<tr> <td colspan="2" style="background-color:#72B3CC;">
<h1>SheenyMa Movie Ticket System</h1>
<table>
	<tr>
		<td>
			<form action="BookingList.php" method="GET">
				<input type="hidden" name="user" value="<?=$_SESSION['user']?>">
				<input type="submit" value="View Reserved Tickets">
			</form>
		</td>
		<td>
			<form action="Logout.php">
				<input type="submit" value="Logout">
			</form>
		</td>
	</tr>
</table>
</td>
</tr>

<?php
putenv('ORACLE_HOME=/oraclient');
$dbh = ocilogon('A0094762', 'crse1410', sid3);
?>

<tr>
<td style="background-color:#eeeeee;">
<table>
<form>
	<tr>
	<td>Movie Title:</td>
	<td><input type="text" name="Title" id="Title"></td>
	</tr>
	
	<tr>
	<td>Cinema:</td>
	<td><input type="text" name="Cinema" id="Cinema"></td>
	</tr>
	
	<tr>
	<td>Date:</td>
	<td>
	<select name="Date"> <option value="">Select Date</option>
	<?php
		$sql = "SELECT DISTINCT mDate FROM MovieTicket ORDER BY mDate ASC";
		$stid = oci_parse($dbh, $sql);
		oci_execute($stid, OCI_DEFAULT);
		
		while($row = oci_fetch_array($stid)) {
			echo "<option value=\"".$row[0]."\">".$row[0]."</option><br>";
		}
		oci_free_statement($stid);
	?>
	</select>
	</td>
	<td><input type="submit" name="formSubmit" value="Search"></td>
	</tr>
</form>
</table>

<?php if (isset ($_GET['formSubmit']))
{
	$title = $_GET['Title'];
	$cinema = $_GET['Cinema'];
	$date = $_GET['Date'];
	if (empty($date))
	{
		if ($title != "" && $cinema != "")
		{
			$sql = "SELECT DISTINCT M.title, M.runtime, M.parating,
						   V.digital, V.threeD, V.language, V.subtitles, M.status,
						   MT.cinema, MT.mDate, MT.mTime, MT.movieID, MT.versionID
					FROM Movie M, Version V, MovieTicket MT
					WHERE M.movieID = V.movieID AND
						  V.movieID = MT.movieID AND
						  V.versionID = MT.versionID AND
						  lower(M.title) like lower('%".$title."%') AND
						  lower(MT.cinema) like lower('%".$cinema."%')
					ORDER BY M.title ASC, MT.cinema ASC, MT.mDate ASC, MT.mTime ASC";
		}
		else if ($title != "")
		{
			$sql = "SELECT DISTINCT M.title, M.runtime, M.parating,
						   V.digital, V.threeD, V.language, V.subtitles, M.status,
						   MT.cinema, MT.mDate, MT.mTime, MT.movieID, MT.versionID
					FROM Movie M, Version V, MovieTicket MT
					WHERE M.movieID = V.movieID AND
						  V.movieID = MT.movieID AND
						  V.versionID = MT.versionID AND
						  lower(M.title) like lower('%".$title."%')
					ORDER BY M.title ASC, MT.cinema ASC, MT.mDate ASC, MT.mTime ASC";
		}
		else if ($cinema != "")
		{
			$sql = "SELECT DISTINCT M.title, M.runtime, M.parating,
						   V.digital, V.threeD, V.language, V.subtitles, M.status,
						   MT.cinema, MT.mDate, MT.mTime, MT.movieID, MT.versionID
					FROM Movie M, Version V, MovieTicket MT
					WHERE M.movieID = V.movieID AND
						  V.movieID = MT.movieID AND
						  V.versionID = MT.versionID AND
						  lower(MT.cinema) like lower('%".$cinema."%')
					ORDER BY MT.cinema ASC, M.title ASC, MT.mDate ASC, MT.mTime ASC";
		}
		else
		{
			$sql = "SELECT 1 FROM DUAL WHERE 1 = 0";
		}
	}
	else
	{
		if ($title != "" && $cinema != "")
		{
			$sql = "SELECT DISTINCT M.title, M.runtime, M.parating,
						   V.digital, V.threeD, V.language, V.subtitles, M.status,
						   MT.cinema, MT.mDate, MT.mTime, MT.movieID, MT.versionID
					FROM Movie M, Version V, MovieTicket MT
					WHERE M.movieID = V.movieID AND
						  V.movieID = MT.movieID AND
						  V.versionID = MT.versionID AND
						  lower(M.title) like lower('%".$title."%') AND
						  lower(MT.cinema) like lower('%".$cinema."%') AND
						  MT.mDate = '".$date."'
					ORDER BY M.title ASC, MT.cinema ASC, MT.mDate ASC, MT.mTime ASC";
		}
		else if ($title != "")
		{
			$sql = "SELECT DISTINCT M.title, M.runtime, M.parating,
						   V.digital, V.threeD, V.language, V.subtitles, M.status,
						   MT.cinema, MT.mDate, MT.mTime, MT.movieID, MT.versionID
					FROM Movie M, Version V, MovieTicket MT
					WHERE M.movieID = V.movieID AND
						  V.movieID = MT.movieID AND
						  V.versionID = MT.versionID AND
						  lower(M.title) like lower('%".$title."%') AND
						  MT.mDate = '".$date."'
					ORDER BY M.title ASC, MT.cinema ASC, MT.mDate ASC, MT.mTime ASC";
		}
		else //($cinema != "")
		{
			$sql = "SELECT DISTINCT M.title, M.runtime, M.parating,
						   V.digital, V.threeD, V.language, V.subtitles, M.status,
						   MT.cinema, MT.mDate, MT.mTime, MT.movieID, MT.versionID
					FROM Movie M, Version V, MovieTicket MT
					WHERE M.movieID = V.movieID AND
						  V.movieID = MT.movieID AND
						  V.versionID = MT.versionID AND
						  lower(MT.cinema) like lower('%".$cinema."%') AND
						  MT.mDate = '".$date."'
					ORDER BY MT.cinema ASC, M.title ASC, MT.mDate ASC, MT.mTime ASC";
		}
	}
	
	//echo "<b>SQL: </b>".$sql."<br><br>";
	echo "<br>";
	$stid = oci_parse($dbh, $sql);
	oci_execute ($stid, OCI_DEFAULT);
	
	$numrows = oci_fetch_all($stid, $rows);
	oci_free_statement($stid);
	if ($numrows == 0)
	{
		echo "No results found.";
	}
	else
	{
		$stid = oci_parse($dbh, $sql);
		oci_execute ($stid, OCI_DEFAULT);
		
		echo "<table border=\"1\">
		<tr>
		<th>Title</th><th>Run Time</th><th>Rating</th>
		<th>Format</th><th>Available In</th><th>Language</th><th>Subtitles</th><th>Status</th>
		<th>Cinema</th><th>Date</th><th>Time</th>
		</tr>";
		
		while ($row = oci_fetch_array($stid)) {
			echo "<tr>";
			echo "<td>".$row[0]."</td>";
			echo "<td>".$row[1]."</td>";
			echo "<td>".$row[2]."</td>";
			
			if ($row[3] == 1)	// if available in digital
			{
				echo "<td>Digital</td>";
			}
			else
			{
				echo "<td>Film</td>";
			}
			
			if ($row[4] == 1)	// if available in 3d
			{
				echo "<td>3D</td>";
			}
			else
			{
				echo "<td>2D</td>";
			}
			
			echo "<td>".$row[5]."</td>";
			echo "<td>".$row[6]."</td>";
			echo "<td>".$row[7]."</td>";
			
			
			if ($row[7] == "Now Showing")
			{
				echo "<td>".$row[8]."</td>";
				echo "<td>".$row[9]."</td>";
				echo "<td>".$row[10]."</td>";
				?>
			<td>
			<button type="button" onClick="window.open('BookTickets.php?movieID=<?=$row[11]?>&versionID=<?=$row[12]?>&cinema=<?=$row[8]?>&mDate=<?=$row[9]?>&mTime=<?=$row[10]?>&email=<?=$_SESSION['user']?>')">
			Find Seats</button>
			</td>
				<?php
			}
			else
			{
				echo "<td>N.A.</td>";
				echo "<td>N.A.</td>";
				echo "<td>N.A.</td>";
			}
			?>
			
			
			
			<?php
		}
		echo "</table>";
		oci_free_statement($stid);
	}
}
?>
</td> </tr>

<?php
	oci_close($dbh);
?>
<tr>
<td colspan="2" style="background-color:#72B3CC; text-align:center;"> Copyright &#169; SheenyMa
</td> </tr>
</table>

</body>
</html>