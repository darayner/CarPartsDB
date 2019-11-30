<?php
require_once('header.php');
require_once('db_connect.php');
?>

<main>
	<?php
		$get_page = $conn->real_escape_string($_GET['page']); //get current page number from url 
		$total_pages = num_total_pages($conn);
		isset($get_page) ? $page = $get_page : $page = 0;
		if ($page > 1){
			$initial = ($page * 60) - 60;
		}
		else{
			$initial = 0;
		}
		if (isset($_SESSION['username'])){ // user is logged in display table
			echo "<div class='table'> 
					<table id='table-results'>
						<tr>
						<th>PartID</th>
						<th>PartName</th>
						<th>PartNumber</th>
						<th>Suppliers</th>
						<th>Category</th>
						<th>Description01</th>
						<th>Description02</th>
						<th>Description03</th>
						<th>Description04</th>
						<th>Description05</th>
						<th>Description06</th>
						<th>Price</th>
						<th>EstimatedShippingCost</th>
						<th>Notes</th>
						<th>ShippingWeight</th>
						</tr>";
				display_user_table($conn, $initial, 60);
				echo "</div></table>";
				for($temp = 1; $temp <= $total_pages + 1; $temp++){
					echo "<div class=pagination><a href=index.php?page=".$temp.">$temp </a></div>";
				}
		
		}
		else{ //visitor logged in display table
			echo "<div class='table'> 
					<table id='table-results'>
						<tr>
						<th>PartName</th>
						<th>PartNumber</th>
						<th>Suppliers</th>
						<th>Category</th>
						<th>Description01</th>
						<th>Description02</th>
						<th>Description03</th>
						<th>Description04</th>
						<th>Description05</th>
						<th>Description06</th>
						<th>Notes</th>
						</tr>";

				display_visitor_table($conn, $initial, 60);
				echo "</div></table>";
				for($temp = 1; $temp <= $total_pages + 1; $temp++){
					echo "<div class=pagination><a href=index.php?page=".$temp.">$temp </a></div>";
				}

		}

		function display_user_table($conn, $start_result, $per_page){ //user table display
			$query = "SELECT * FROM carparts LIMIT ".$start_result.','.$per_page;
			$result = $conn->query($query);

			if ($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					echo "<tr><td>".$row['PartID']."</td>"."<td>".$row['PartName']."</td>"."<td>".$row['PartNumber']."</td>"."<td>".$row['Suppliers']."</td>"."<td>".$row['Category']."</td>"."<td>".$row['Description01']."</td>"."<td>".$row['Description02']."</td>"."<td>".$row['Description03']."</td>"."<td>".$row['Description04']."</td>"."<td>".$row['Description05']."</td>"."<td>".$row['Description06']."</td>"."<td>".$row['Price']."</td>"."<td>".$row['Estimated Shipping Cost']."</td>"."<td>".$row['Notes']."</td>"."<td>".
						$row['Shipping Weight']."</td></tr>";
				}
			}
			else{
				die("Error:".$conn->error);
			}
			$conn->close();
		}

		function display_visitor_table($conn, $start_result, $per_page){ // visitor table display
			$query = "SELECT * FROM carparts LIMIT ".$start_result.','.$per_page;
			$result = $conn->query($query);

			if ($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					echo "</td>"."<td>".$row['PartName']."</td>"."<td>".$row['PartNumber']."</td>"."<td>".$row['Suppliers']."</td>"."<td>".$row['Category']."</td>"."<td>".$row['Description01']."</td>"."<td>".$row['Description02']."</td>"."<td>".$row['Description03']."</td>"."<td>".$row['Description04']."</td>"."<td>".$row['Description05']."</td>"."<td>".$row['Description06']."</td>"."</td>"."<td>".$row['Notes']."</td>"."</tr>";
				}
			}
			else{
				die("Error:".$conn->error);
			}
			$conn->close();

		}

		function num_total_pages($conn){
			$query = "SELECT PartID FROM carparts";
			$result = $conn->query($query);
			$num_results = $result->num_rows;

			return $num_results/60; // total number of pages
	}
	
	?>

</main>


