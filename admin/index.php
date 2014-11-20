<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>QR-Rallye Administration</title>
</head>
<body>

<div align="center">
	<h2>QR-Rallye Administration Panel</h2>
	<hr />
</div>

<div>
	<h3>Add New Rallye</h3>
	<form action="index.php" method="post">
		<table>
			<tr>
				<td>Rallye Name*:</td>
				<td><input type="text" name="rName" value="Name" /></td>
			</tr>
			<tr>
				<td>Rallye Start:</td>
				<td><input type="text" name="rStart" /> [YYYY-MM-DD hh:mm:ss]</td>
			</tr>
			<tr>
				<td>Rallye End:</td>
				<td><input type="text" name="rEnd" /> [YYYY-MM-DD hh:mm:ss]</td>
			</tr>
			<tr>
				<td>Snippets delay:</td>
				<td><input type="text" name="rSnippetsDelay" /> [sec.]</td>
			</tr>
			<tr>
				<td colspan="2"><b>Administration</b></td>
			</tr>
			<tr>
				<td>E-MaiL*:</td>
				<td><input type="text" name="rMail" value="test@mail.com"/></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><input type="password" name="rPassword" /></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" name="new" value="Add rallye" /></td>
			</tr>
		</table>		
		* not optional
	</form>
</div>

<hr />
<?php
	include "newrallye.php";
?>
<hr />

<div>
	<h3>Edit Rallye</h3>
	<form action="index.php" method="post">
		<table>
			<tr>
				<td>Rallye Name*:</td>
				<td><input type="text" name="rName" value="Name" /></td>
			</tr>
			<tr>
				<td>E-MaiL*:</td>
				<td><input type="text" name="rMail" value="test@mail.com"/></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><input type="password" name="rPassword" /></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" name="edit" value="Edit rallye" /></td>
			</tr>
		</table>		
		* not optional
	</form>
</div>

<hr />
<?php
	include "editrallye.php";
?>
<hr />

</body>
</html>