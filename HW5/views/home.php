<script type="text/javascript">
	function addtocart(pid){
		document.form1.productid.value=pid;
		document.form1.command.value='add';
		document.form1.submit();
	}
</script>

<div class="content">

	<h2> <?php echo $title; ?> </h2>

	<form name="form1">
		<input type="hidden" name="productid" />
	    <input type="hidden" name="command" />
	</form>

	<div id="salestable">
	<h3>Special Sales!</h3>
	
	<?php
		$sql = 'SELECT * FROM products, sales WHERE products.pID = sales.pID ';
		
		//$res = mysql_query($sql);

		$res = $this->db->query($sql);

		//print_r($res); //for debugging
		//while( $row = mysql_fetch_assoc($res) ) {


		/*prints out table of items on sale*/
		foreach ($res->result_array() as $row) {
			//print_r($row); //for debugging
			$number = $row['pPrice']*(1-$row['discount']);
			$saleprice = number_format($number, 2, '.', '');
			$imgloc = base_url().$row['imagelocation'];
			echo '<table style="margin:5px;display:inline-table;border:1px solid black;width:350px;vertical-align:top;">';
			echo '<tr style="height:155px;">';
			echo "<td style='width:155px;'><img src='$imgloc' alt='$row[imagelocation]' style='border:1px solid black;'></td>";
			echo "<td>$row[pName]<br/>
					Sale Price: &#36;$saleprice <br/>
					Sale Ends: $row[enddate] <br/> <br/>
					<input type='button' name='addcart' value='Add to Cart' onClick='addtocart($row[pID])'></td>"; 
			echo '</tr> <tr>';
			echo "<td colspan='2'></td>";
			echo '</tr> <tr style="height:100px">';
			echo "<td colspan='2' style='vertical-align:top;'>$row[pDescrip]</td> ";
			echo '</tr>';
			echo '</table>';
		}
		
		//mysql_close($con);	
	?>
	</div>

</div>