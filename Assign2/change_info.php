
<?php
    require_once('header.php');
    require_once('db_connect.php');
    echo $_POST['Shipping Weight']."<br>";
    echo $_POST['Estimated Shipping Cost']."<br>";    
    
    if($_SESSION['username'] != "admin"){
        die("Invalid username");
    }
    
    if($_COOKIE["PHPSESSID"] != $_POST['sessionId'] && isset($_POST['sessionID'])){
        die("Invalid session ID");
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
            echo "Changes successful!";
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
                <th>Finalize</th>
		</tr>";
    echo "<form action = 'change_info.php?PartID=".$_GET["PartID"]."' method = 'POST' id='editForm'>
            <tr>
            <td>" . $_GET['PartID'] ."
            <td><textarea rows='1' cols='8' class = 'editBox' name='PartName'  form='editForm' maxlength= '65'>" .$row['PartName']." </textarea></td>
            <td><textarea rows='1' cols='8' class = 'editBox' name='PartNumber'  form='editForm' maxlength= '13'>" .$row['PartNumber']." </textarea></td>
            <td><textarea rows='1' cols='8' class = 'editBox' name='Suppliers'  form='editForm' maxlength= '12'>" .$row['Suppliers']." </textarea></td>
            <td><textarea rows='1' cols='8' class = 'editBox' name='Category'  form='editForm' maxlength= '26'>" .$row['Category']." </textarea></td>
            <td><textarea rows='1' cols='8' class = 'editBox' name='Description01'  form='editForm' maxlength= '155'>" .$row['Description01']." </textarea></td>
            <td><textarea rows='1' cols='8' class = 'editBox' name='Description02'  form='editForm' maxlength= '187'>" .$row['Description02']." </textarea></td>
            <td><textarea rows='1' cols='8' class = 'editBox' name='Description03'  form='editForm' maxlength= '154'>" .$row['Description03']." </textarea></td>
            <td><textarea rows='1' cols='8' class = 'editBox' name='Description04'  form='editForm' maxlength= '121'>" .$row['Description04']." </textarea></td>
            <td><textarea rows='1' cols='8' class = 'editBox' name='Description05'  form='editForm' maxlength= '162'>" .$row['Description05']." </textarea></td>
            <td><textarea rows='1' cols='8' class = 'editBox' name='Description06'  form='editForm' maxlength= '104'>" .$row['Description06']." </textarea></td>
            <td><textarea rows='1' cols='8' class = 'editBox' name='Price'  form='editForm' maxlength= '8'>" .$row['Price']." </textarea></td>
            <td><textarea rows='1' cols='8' class = 'editBox' name='Estimated Shipping Cost'  form='editForm' maxlength= '5'>" .$row['Estimated Shipping Cost']." </textarea></td>
            <td><textarea rows='1' cols='8' class = 'editBox' name='Notes'  form='editForm' maxlength= '50'>" .$row['Notes']." </textarea></td>
            <td><textarea rows='1' cols='8' class = 'editBox' name='Shipping Weight'  form='editForm' maxlength= '3'>" .$row['Shipping Weight']." </textarea></td>
                <input type = 'hidden' id = 'sessionId' name = 'sessionId' value = ''>
            
            <td> <button type='submit' name='submit-edit' id='submitFilter' onclick = 'cookieHandler()'>Submit</button> </td>
        </form></tr></table></div>";
    ?>
<script>
        function cookieHandler(){
            document.getElementById("sessionId").value = document.cookie.substring(document.cookie.length-26);
        }
</script>