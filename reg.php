
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html" />
		<title>Register Page</title>
		<style type="text/css">
		    html{font-size:12px;}
		    fieldset{width:520px; margin: 0 auto;}
		    legend{font-weight:bold; font-size:14px;}
		    label{float:left; width:70px; margin-left:10px;}
		    .left{margin-left:80px;}
		    .input{width:150px;}
		    span{color: #666666;}
		</style>
		<script language=JavaScript>
			function InputCheck(RegForm)
			{
			  if (RegForm.username.value == "")
			  {
			    alert("Username cannot be empty!");
			    RegForm.username.focus();
			    return (false);
			  }
			  if (RegForm.password.value == "")
			  {
			    alert("Password cannot be empty!");
			    RegForm.password.focus();
			    return (false);
			  }
			  if (RegForm.repass.value != RegForm.password.value)
			  {
			    alert("Please repeat same password!");
			    RegForm.repass.focus();
			    return (false);
			  }
			  if (RegForm.email.value == "")
			  {
			    alert("Email address cannot be empty!");
			    RegForm.email.focus();
			    return (false);
			  }
			}
		</script>
	</head>
	<body>
		<div>
			<fieldset>
				<legend>User Register</legend>
				<form name="RegForm" method="post" action="modules/login_reg/reg.php" onSubmit="return InputCheck(this)">
					<p>
						<label for="username" class="label">Username:</label>
						<input id="username" name="username" type="text" class="input" />
						<span>(*Cannot be empty, 5~32 characters, must start with letter, letters and numbers only)</span>
					<p/>
					<p>
						<label for="password" class="label">Password:</label>
						<input id="password" name="password" type="password" class="input" />
						<span>(*Cannot be empty, at least 6 characters)</span>
					<p/>
					<p>
						<label for="repass" class="label">Repeated password:</label>
						<input id="repass" name="repass" type="password" class="input" />
					<p/>
					<p>
						<label for="email" class="label">Email address:</label>
						<input id="email" name="email" type="text" class="input" />
						<span>(*Cannot be empty)</span>
					<p/>
					<p>
						<input type="submit" name="submit" value="  SUBMIT  " class="left" />
					</p>
				</form>
				<div class="user_list">
					<table border="1">
					<tr>
						<th>User ID</th>
						<th>Username</th>
						<th>Email</th>
						<th>Register Date</th>
					</tr>
						<?php 
							date_default_timezone_set("America/Halifax");
							include('modules/login_reg/conn.php');
							$query = mysql_query("select * from user");
							while ( $db_field = mysql_fetch_assoc($query) ) {
							echo "<tr>";
							echo "<td align = \"center\">" . $db_field['uid'] . "</td>";
							echo "<td align = \"center\">" . $db_field['username'] . "</td>";
							echo "<td align = \"center\">" . $db_field['email'] . "</td>";
							echo "<td align = \"center\">" . date("Y-m-d", $db_field['regdate']) . "</td>";
							echo "</tr>";
							}
						?>
					</table>
				</div>
			</fieldset>
		</div>
	</body>
</html>