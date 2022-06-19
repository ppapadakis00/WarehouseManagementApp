<?php   
ob_start();
session_start();
    include("Setup.php");
    include("functions.php");

    $user_data = check_login($con);

    if($user_data==0){
        header("Location: index.php");
   }else{
       if($user_data['property']!='admin'){
            header("Location: index.php");
       }
   }

    $warnInfo ="";
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        if(isset($_POST['order'])){
            $Product_ID = $_POST['product_id'];
            $Provider_ID = $_POST['provider_id'];
            $Quantity = $_POST['quantity'];
            if(!empty($Product_ID) && !empty($Provider_ID) && !empty($Quantity)){
                $query = "INSERT INTO ordersto (product_id,provider_id,quantity) values (?,?,?)";
                $stm = $con->prepare($query);
                $stm->bind_param("iii",$Product_ID,$Provider_ID,$Quantity);
                $stm->execute();
                unset($_POST['order']);
                header("Location: index.php");
            }else{
                $warnInfo ="Please enter Valid info.<br><br>";
            }
        }
        if(isset($_POST['return'])){
            header("Location: index.php");
            unset($_POST['return']);
        }
    }

?>


<html>
    <head>
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body class="NewProduct-Body">
        <div class="NewProduct-Base">
            <form action="order.php" method="POST" class="AddProduct-form">
                <div class="NewProduct-Title">Make an Order</div>
                <label for="product_name">Product Name</label>
                <select name="product_id" id="product_id" class="productsList">
                    <optgroup>
                        <?php $result = mysqli_query($con,"SELECT * FROM products ORDER BY id DESC");
                        while($row = mysqli_fetch_array($result)){ 
                        ?>
                        <option value="<?=$row['id']?>"><?=$row['product_name']?></option>
                        <?php } ?>
                    </optgroup>
                </select>
                <label for="provider">Provider</label>
                <select name="provider_id" id="provider_id" class="productsList">
                    <optgroup>
                        <?php $result1 = mysqli_query($con,"SELECT * FROM users WHERE users.property = 'provider' ORDER BY user_id DESC");
                        while($row1 = mysqli_fetch_array($result1)){ 
                        ?>
                        <option value="<?=$row1['user_id']?>"><?=$row1['first_name']?> <?=$row1['second_name']?></option>
                        <?php } ?>
                    </optgroup>
                </select>
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" class="inputFields">
                 <span class="Warnings-messages"><?=$warnInfo?></span>
                <div class="NewProduct-buttons">
                    <button type="submit" name="order">Order</button>
                    <button type="submit" name="return">Return</button>
                </div>
            </form>
        </div>
    </body>
</html>