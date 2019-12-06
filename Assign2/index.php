<?php
require_once('header.php');
require_once('db_connect.php');
?>

<main>
	<?php
        echo 'Filter by: <form action="" method="get" name="filter" id="filter">
                                    <input type="radio" name="filterType" value="Category"> Category 
                                    <input type="radio" name="filterType" value="Suppliers"> Suppliers 
                                    <input type="radio" name="filterType" value="None"> None <br>
                                    Filter
                                    <input type="text" name="filterText">
                                    <button type="submit" name="submit-filter" id="submitFilter">Submit</button>
                                </form>';
                $filterType = $_GET['filterType'];
                $filterText = $_GET['filterText'];

        $get_order = $conn->real_escape_string($_GET['order']); //check column to order
        $get_sort = $conn->real_escape_string($_GET['sort']);

        if(isset($get_order) && !empty($get_order)){ 
            $order = $get_order;
        }
        else{
            $order = 'PartID';
        }

        if(isset($get_sort) && !empty($get_sort)){
            $sort = $get_sort;
        }
        else{
            $sort = 'ASC';
        }

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
            $same_page = $conn->real_escape_string($_GET['sp']);

            if (isset($same_page) && !empty($same_page)){
                if($same_page == 'true'){
                    $sort == 'DESC' ? $sort = 'ASC' : $sort = 'DESC';
                }
            }

			echo "<div class='table'> 
                    <table id='table-results'>
                        <tr>
                        <th><a href='index.php?order=PartID&sort=$sort&page=$page&sp=true'>PartID</th>
                        <th>PartName</th>
                        <th>PartNumber</th>
                        <th><a href='index.php?order=Suppliers&sort=$sort&page=$page&sp=true'>Suppliers</th>
                        <th><a href='index.php?order=Category&sort=$sort&page=$page&sp=true'>Category</th>
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

				display_user_table($conn, $initial, 60, $order, $sort);
				echo "</div></table>";
				$temp = explode(" ", $filterText);
                                $filterText = '';
                                for($i = 0; $i < sizeof($temp); $i++){
                                    $filterText = $filterText . $temp[$i] . "+";
                                }
                                $filterText = substr($filterText,0, strlen($filterText)-1);
				for($temp = 1; $temp <= $total_pages + 1; $temp++){
					echo "<div class=pagination><a href=index.php?order=$order&sort=$sort&filterText=".$filterText."&filterType=".$filterType."&page=".$temp.">$temp </a></div>";
				}
		
		}
		else{ //visitor logged in display table
            $same_page = $conn->real_escape_string($_GET['sp']);

            if (isset($same_page) && !empty($same_page)){
                if($same_page == 'true'){
                    $sort == 'DESC' ? $sort = 'ASC' : $sort = 'DESC';
                }
            }
			echo "<div class='table'> 
					<table id='table-results'>
						<tr>
                        <th>PartName</th>
                        <th>PartNumber</th>
                        <th><a href='index.php?order=Suppliers&sort=$sort&page=$page&sp=true'>Suppliers</th>
                        <th><a href='index.php?order=Category&sort=$sort&page=$page&sp=true'>Category</th>
                        <th>Description01</th>
                        <th>Description02</th>
                        <th>Description03</th>
                        <th>Description04</th>
                        <th>Description05</th>
                        <th>Description06</th>
                        <th>Notes</th>
						</tr>";

				display_visitor_table($conn, $initial, 60, $order, $sort);
				echo "</div></table>";
                                $temp = explode(" ", $filterText);
                                $filterText = '';
                                for($i = 0; $i < sizeof($temp); $i++){
                                    $filterText = $filterText . $temp[$i] . "+";
                                }
                                $filterText = substr($filterText,0, strlen($filterText)-1);
				for($temp = 1; $temp <= $total_pages + 1; $temp++){
					echo "<div class=pagination><a href=index.php?filterText=".$filterText."&filterType=".$filterType."&page=".$temp.">$temp</a></div>";
				}

		}

		function display_user_table($conn, $start_result, $per_page, $order, $sort){ //user table display
                        global $filterType, $filterText;
                        if(empty($filterType) || empty($filterText) || $filterType == "None"){
                            $query = "SELECT * FROM carparts ORDER BY $order $sort LIMIT ".$start_result.','.$per_page;
                            $result = $conn->query($query);
                        }
                        else{
                            if($filterType == "Suppliers"){
                                $query = "SELECT * FROM carparts WHERE Suppliers = ? ORDER BY $order $sort LIMIT ".$start_result.','.$per_page;
                            }
                            else {
                                $query = "SELECT * FROM carparts WHERE Category = ? ORDER BY $order $sort LIMIT ".$start_result.','.$per_page;
                            }
                            if($stmt = $conn->prepare($query)){
                                $stmt->bind_param("s", $filterText);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $stmt->close();
                            }
                            else{
                                echo "Did not complete search";
                            }
                        }
                        

                        if($_SESSION['username'] == "admin"){
                            echo "Hello Admin, Click the name of an entry to edit it";
                        }
			if ($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
                                        if($_SESSION['username'] == "admin"){
                                            $_SESSION['PartID'] = $row['PartID'];
                                            echo "<tr><td>".$row['PartID']."</td>"."<td><a href = change_info.php?PartID=".$row['PartID'].">".$row['PartName']."</a></td>"."<td>".$row['PartNumber']."</td>"."<td>".$row['Suppliers']."</td>"."<td>".$row['Category']."</td>"."<td>".$row['Description01']."</td>"."<td>".$row['Description02']."</td>"."<td>".$row['Description03']."</td>"."<td>".$row['Description04']."</td>"."<td>".$row['Description05']."</td>"."<td>".$row['Description06']."</td>"."<td>".$row['Price']."</td>"."<td>".$row['Estimated Shipping Cost']."</td>"."<td>".$row['Notes']."</td>"."<td>".
						$row['Shipping Weight']."</td></tr>";
                                        }else{
                                            echo "<tr><td>".$row['PartID']."</td>"."<td>".$row['PartName']."</td>"."<td>".$row['PartNumber']."</td>"."<td>".$row['Suppliers']."</td>"."<td>".$row['Category']."</td>"."<td>".$row['Description01']."</td>"."<td>".$row['Description02']."</td>"."<td>".$row['Description03']."</td>"."<td>".$row['Description04']."</td>"."<td>".$row['Description05']."</td>"."<td>".$row['Description06']."</td>"."<td>".$row['Price']."</td>"."<td>".$row['Estimated Shipping Cost']."</td>"."<td>".$row['Notes']."</td>"."<td>".
						$row['Shipping Weight']."</td></tr>";
                                        }
				}
			}
			else{
                                if($conn->error){
                                    die("Error:".$conn->error);
                                } else{
                                    die("Error: Query returned no results");
                                }
			}
			$conn->close();
		}

		function display_visitor_table($conn, $start_result, $per_page, $order, $sort){ // visitor table display
                    global $filterType, $filterText;
                        if(empty($filterType) || empty($filterText) || $filterType == "None"){
                            $query = "SELECT * FROM carparts ORDER BY $order $sort LIMIT ".$start_result.','.$per_page;
                            $result = $conn->query($query);
                        }
                        else{
                            if($filterType == "Suppliers"){
                                $query = "SELECT * FROM carparts WHERE Suppliers = ? ORDER BY $order $sort LIMIT ".$start_result.','.$per_page;
                            }
                            else {
                                $query = "SELECT * FROM carparts WHERE Category = ? ORDER BY $order $sort LIMIT ".$start_result.','.$per_page;
                            }
                            if($stmt = $conn->prepare($query)){
                                $stmt->bind_param("s", $filterText);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $stmt->close();
                            }
                            else{
                                echo "Did not complete search";
                            }
                        }
                        

			if ($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					echo "</td>"."<td>".$row['PartName']."</td>"."<td>".$row['PartNumber']."</td>"."<td>".$row['Suppliers']."</td>"."<td>".$row['Category']."</td>"."<td>".$row['Description01']."</td>"."<td>".$row['Description02']."</td>"."<td>".$row['Description03']."</td>"."<td>".$row['Description04']."</td>"."<td>".$row['Description05']."</td>"."<td>".$row['Description06']."</td>"."</td>"."<td>".$row['Notes']."</td>"."</tr>";
				}
			}
			else{
				if($conn->error){
                                    die("Error:".$conn->error);
                                } else{
                                    die("Error: Query returned no results");
                                }
			}
			$conn->close();

		}

		function num_total_pages($conn){
                        global $filterType, $filterText;
                        if(isset($filterType) && isset($filterText) && $filterType != "None" && $filterType != '' && ($filterType == "Category" || $filterType == "Suppliers")){
                            $query = "SELECT PartID FROM carparts WHERE ".$filterType." = '".$filterText."'";
                        } else{
                            $query = "SELECT PartID FROM carparts";
                        }
                        $result = $conn->query($query);
			$num_results = $result->num_rows;

			return $num_results/60; // total number of pages
	}
	
	?>

</main>


