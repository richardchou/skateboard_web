<script type="text/javascript">
	function addtocart(pid){
		document.form1.productid.value=pid;
		document.form1.command.value='add';
		document.form1.submit();
	}
</script>

<div class="content">
<h2> Home Page </h2>

<form name="form1">
	<input type="hidden" name="productid" />
    <input type="hidden" name="command" />
</form>
	<div id="category">
	<?php
		$category = '';
		$sqlpid = "SELECT pCategory FROM productcategory WHERE pCategoryID = '$categoryid'";
		$respid = $this->db->query($sqlpid);
		if ( $respid->num_rows() > 0 ) {
			$rowpid = $respid->row();
			$category = $rowpid->pCategory;
			//print_r($rowpid->pCategory);
		}
		else {
			die( 'Error: '.$this->db->_error_message() );
		}
		//print_r($sqlpid.'<br/>'); //debug
		//print_r($respid); // debug
		
		
		$sql = "SELECT * FROM products WHERE products.pCategory = '$category' AND products.pID NOT IN(SELECT pID FROM sales)";
		
		echo "<h3>$category</h3>";
		
		//$res = mysql_query($sql);
		$result = $this->db->query($sql);
		//print_r($result); //for debugging
		//while( $row = mysql_fetch_assoc($res) ) {
		foreach ( $result->result_array() as $row ) {
			$imgloc = base_url().$row['imagelocation'];
			echo '<table style="margin:5px;display:inline-table;border:1px solid black;width:350px;vertical-align:top;">';
			echo '<tr style="height:155px;">';
			echo "<td style='width:155px;'><img src='$imgloc' alt='$row[imagelocation]' style='border:1px solid black;'></td>";
			echo "<td>$row[pName]<br/>
					Price: &#36;$row[pPrice] <br/>
					<input type='button' name='addcart' value='Add to Cart' onClick='addtocart($row[pID])'></td>"; 
			echo '</tr> <tr>';
			echo "<td colspan='2'></td>";
			echo '</tr> <tr style="height:100px">';
			echo "<td colspan='2' style='vertical-align:top;'>$row[pDescrip]</td> ";
			echo '</tr>';
			echo '</table>';
		}
		
		
		
	?>
	
	</div>
	<br/>
	<hr/>
	
	<div id="salestable">
	
	
	<?php
		
		$sql = "SELECT * FROM products, sales, productcategory 
				WHERE products.pID = sales.pID 
				AND products.pCategory = '$category' 
				AND productcategory.pCategoryID='$categoryid'";

		
		//if ( $res = mysql_query($sql) ) {
		$res = $this->db->query($sql);
		if ( $res->num_rows > 0) {
		
			echo '<h4> Special Sales </h4>';
			//while( $row = mysql_fetch_assoc($res) ) {
			foreach ( $res->result_array() as $row ) {
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
		}
		
	?>
	</div>
	

</div>

</div>

</body>