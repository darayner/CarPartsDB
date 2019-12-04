<?php
    require_once('header.php');
    require_once('db_connect.php');
    echo $_POST['Shipping Weight']."<br>";
    echo $_POST['Estimated Shipping Cost']."<br>";
    
    if($_SESSION['username'] != "admin"){
        die("Invalid username");
    }
    
    if(isset($_POST['PartName'])){
        $sql = "UPDATE carparts SET PartName = ? ,PartNumber = ? , Suppliers = ?, " 
                . "Category = ?, Description01 = ?, Description02 = ?, Description03 = ?, "
                . "Description04 = ?, Description05 = ?, Description06 = ?, Price = ?, "
                . "`Estimated Shipping Cost` = ?, Notes = ?, `Shipping Weight` = ? WHERE PartID = ?;";
        
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("ssssssssssssssi", $_POST['PartName'],$_POST['PartNumber'],$_POST['Suppliers'],$_POST['Category'],$_POST['Description01'],
                    $_POST['Description02'],$_POST['Description03'],$_POST['Description04'],$_POST['Description05'],$_POST['Description06'],
                    $_POST['Price'],$_POST['Estimated_Shipping_Cost'],$_POST['Notes'],$_POST['Shipping_Weight'],$_GET['PartID']); 
            $stmt->execute();
        }
        else{
            echo "fail";
        
        }
        
    }
    
    echo "<a href = 'index.php'> Return to table </a>";
    $query = "SELECT * FROM carparts WHERE partID = ?";
    if($stmt = $conn->prepare($query)){
        $stmt->bind_param("i", $_GET['PartID']);
        $stmt->execute();
        $row = $stmt->get_result();
        $row = $row->fetch_assoc();
        $stmt->close();
    }
    else {
        die("Invalid part ID");
    }
    
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
		<th>Estimated Shipping Cost</th>
		<th>Notes</th>
		<th>Shipping Weight</th>
		</tr>";
    echo "<form action = 'change_info.php?PartID=".$_GET["PartID"]."' method = 'POST'>
            <tr>
            <td>" . $_GET['PartID'] ."
            <td> <input type = 'text' class ='editBox' name = 'PartName' value = '".$row['PartName']."'></td>
            <td> <input type = 'text' class ='editBox' name = 'PartNumber' value ='".$row['PartNumber']."'></td>
            <td> <input type = 'text' class ='editBox' name = 'Suppliers' value ='".$row['Suppliers']."'></td>
            <td> <input type = 'text' class ='editBox' name = 'Category' value ='".$row['Category']."'></td>
            <td> <input type = 'text' class ='editBox' name = 'Description01' value ='".$row['Description01']."'></td>
            <td> <input type = 'text' class ='editBox' name = 'Description02' value ='".$row['Description02']."'></td>
            <td> <input type = 'text' class ='editBox' name = 'Description03' value ='".$row['Description03']."'></td>
            <td> <input type = 'text' class ='editBox' name = 'Description04' value ='".$row['Description04']."'></td>
            <td> <input type = 'text' class ='editBox' name = 'Description05' value ='".$row['Description05']."'></td>
            <td> <input type = 'text' class ='editBox' name = 'Description06' value ='".$row['Description06']."'></td>
            <td> <input type = 'text' class ='editBox' name = 'Price' value ='".$row['Price']."'></td>
            <td> <input type = 'text' class ='editBox' name = 'Estimated Shipping Cost' value ='".$row['Estimated Shipping Cost']."'></td>
            <td> <input type = 'text' class ='editBox' name = 'Notes' value ='".$row['Notes']."'></td>
            <td> <input type = 'text' class ='editBox' name = 'Shipping Weight' value ='".$row['Shipping Weight']."'></td>
            <td> <button type='submit' name='submit-edit' id='submitFilter'>Submit</button> </td>
        </form></tr></table></div>";
    echo "Shipping Cost: ".$row['Estimated Shipping Cost']."<br>Shipping Weight: ".$row['Shipping Weight'];
    