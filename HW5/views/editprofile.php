<script type="text/javascript">
	function validate (form, id) {
		var errmsg = "";
		var valid = true;
		var v1 = isValidEmail(form.email);
		var v2 = isValidStr(form.pw);
		var v3 = isValidStr(form.firstname);
		var v4 = isValidStr(form.lastname);
		
		if (!v1 && !v2 && !v3 && !v4) {
			errmsg = "Must fill out at least one field to edit profile.";
		}
		else {
		
			if (!v1 && (form.email.value.length > 0) ){
				errmsg += "Invalid e-mail format.";
			}
		
		}
		
		if (errmsg.length > 0 ){
			valid = false;
			document.getElementById(id).innerHTML=errmsg;
		}
		//alert(valid + " " + errmsg.length);
		return valid;	
	}
</script>

<div class="content">

	<form name="edit" method="POST" action="<?php echo base_url();?>index.php/home/editprofile" 
		style="position:relative;top:10px;left:50px;padding-bottom:10px;">

		<h2><?php echo $title;?></h2>
		<table >
			<tr>
				<td>New E-mail (Login): </td>
				<td><input type="text" name="email"/> </td>
			</tr>
			<tr>
				<td>New First Name: </td>
				<td><input type="text" name="firstname"/> </td>
			</tr>
			<tr>
				<td>New Last Name: </td>
				<td><input type="text" name="lastname"/> </td>
			</tr>
			<tr>
				<td>New Password: </td>
				<td><input type="password" name="pw"/> </td>
			</tr>

			<tr>
				<td><br/><input type="submit" name="edit" value="Edit Profile" onclick="return validate(this.form, 'errmsg')"></td>
			</tr>
		</table>
		
		<br/>
		<div id="errmsg" style="position:relative;top:5px;left:50px;color:red"> 
		<?php 
			if(isset($_POST['edit'])) {
				echo $errmsg; 
			}
		?>
		</div>
		
	</form>
</div>