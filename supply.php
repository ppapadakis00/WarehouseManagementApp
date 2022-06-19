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
            $Store_ID = $_POST['store_id'];
            $Quantity = $_POST['quantity'];
            if(!empty($Product_ID) && !empty($Store_ID) && !empty($Quantity)){
                if($Quantity>0){
                    $queryResults1 = mysqli_query($con,"SELECT * FROM products WHERE products.id = $Product_ID");
                    $rowRes1 = mysqli_fetch_array($queryResults1);
                    if($Quantity<=$rowRes1['garage_quantity']){
                        $queryResults = mysqli_query($con,"SELECT * FROM products_quantity WHERE products_quantity.store_id = $Store_ID AND products_quantity.product_id = $Product_ID");
                        $rowRes = mysqli_fetch_array($queryResults);
                        $tempQuantity = $rowRes1['garage_quantity'] - $Quantity;
                        $AfterQuantity = $Quantity + $rowRes['quantity'];
                        
                        $queryResults2 = mysqli_query($con,"SELECT COUNT(id) AS exist FROM products_quantity WHERE products_quantity.store_id = $Store_ID AND products_quantity.product_id = $Product_ID");
                        $rowRes2 = mysqli_fetch_array($queryResults2);

                        if($rowRes2['exist']>0){
                            $query = "UPDATE products_quantity SET products_quantity.quantity=$AfterQuantity WHERE products_quantity.store_id = $Store_ID AND products_quantity.product_id = $Product_ID";
                            mysqli_query($con,$query);
                        }else{
                            $query = "INSERT INTO products_quantity (product_id,store_id,quantity,min_quantity) values ($Product_ID,$Store_ID,$Quantity,$Quantity)";
                            mysqli_query($con,$query);
                        }

                        mysqli_query($con,"UPDATE products SET products.garage_quantity=$tempQuantity WHERE products.id = $Product_ID");
                        unset($_POST['order']);
                        header("Location: index.php");
                    }
                }else{
                    $warnInfo ="Please enter Valid info.<br><br>";
                }
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
            <form action="supply.php" method="POST" class="AddProduct-form">
                <div class="NewProduct-Title">Make a Supply</div>
                <label for="store_id">Store</label>
                <select name="store_id" id="store_id" class="productsList">
                    <optgroup>
                        <?php $result1 = mysqli_query($con,"SELECT * FROM stores ORDER BY id DESC");
                        while($row1 = mysqli_fetch_array($result1)){ 
                        ?>
                        <option value="<?=$row1['id']?>" <?php if(isset($_POST['store_id'])){if($_POST['store_id']==$row1['id']){echo "selected";}}?>><?=$row1['store_name']?></option>
                        <?php } ?>
                    </optgroup>
                </select>
                <label for="product_name">Product Name</label>
                <select name="product_id" id="product_id" class="productsList">
                    <optgroup>
                        <?php $result = mysqli_query($con,"SELECT * FROM products ORDER BY id DESC");
                        while($row = mysqli_fetch_array($result)){ 
                        ?>
                        <option value="<?=$row['id']?>" <?php if(isset($_POST['product_id'])){if($_POST['product_id']==$row['id']){echo "selected";}}?>><?=$row['product_name']?></option>
                        <?php } ?>
                    </optgroup>
                </select>
                <div class="NewProduct-buttons" style="padding: 10px 0px;">
                    <button type="submit" name="confirm">Confirm</button>
                </div>
                <?php if(isset($_POST['product_id'])){ 
                    $tempProduct_ID = $_POST['product_id'];
                    $MaxResults = mysqli_query($con,"SELECT * FROM products WHERE products.id = $tempProduct_ID");
                    $rowMax = mysqli_fetch_array($MaxResults);
                    ?>
                <label for="quantity">Quantity (max:<?=$rowMax['garage_quantity']?>)</label>
                <input type="number" name="quantity" class="inputFields">
                 <span class="Warnings-messages"><?=$warnInfo?></span>
                <div class="NewProduct-buttons">
                    <button type="submit" name="order">Supply</button>
                    <button type="submit" name="return">Return</button>
                </div>
                <?php }?>
            </form>
        </div>
    </body>
</html>