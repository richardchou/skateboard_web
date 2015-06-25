<script type="text/javascript">
	function validate (form, id) {
		var errmsg = "";
		var valid = true;
		if (!isValidStr(form.email) ){
			errmsg = "Please complete all fields before logging in.";
		}
		
		if (!isValidStr(form.pw) ){
			errmsg = "Please complete all fields before logging in. ";
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

<form name="login" method="POST" action="<?php echo base_url();?>index.php/home/login" 
	style="position:relative;top:10px;left:50px;padding-bottom:10px;">

	<h2><?php echo $title; ?></h2>
	<table >
		<tr>
			<td>E-mail (Login): </td>
			<td><input type="text" name="email"/> </td>
		</tr>
		<tr>
			<td>Password: </td>
			<td><input type="password" name="pw"/> </td>
		</tr>
		<tr>
			<td><br/><input type="submit" name="login" value="Login" onClick="return validate(this.form, 'errmsg')"></td>
		</tr>
	</table>
	
	
	<br/>
	<div id="errmsg" style="position:relative;top:5px;left:50px;color:red"> 
	<?php 
		if(isset($_POST['login'])) {
			echo $errmsg; 
		}
	?>
	</div>
	
</form>
</div>