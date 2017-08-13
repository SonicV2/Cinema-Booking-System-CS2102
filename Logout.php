<?php
	session_start();
	session_destroy();
?>
	
<html>
    <head>
        <title>SheenyMa- Logged Out</title>
    </head>
    <body>
		<table>
		<tr> <td colspan="2" style="background-color:#72B3CC;">
		<h1>SheenyMa- Logged Out</h1>
		</td> </tr>
		<tr><td style="background-color:#eeeeee;" width="60%">
		
		You have logged out. Thank you for using SheenyMa!<br><br>
		
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