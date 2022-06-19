<?php   
ob_start();
session_start();
    include("Setup.php");
    include("functions.php");

    $user_data = check_login($con);
    $warnLInfo="";
    if($user_data==0){
        header("Location: index.php");
   }else{
       if($user_data['property']!='admin'){
            header("Location: index.php");
       }
   }
   $user_ID = $user_data['user_id'];


   if($_SERVER['REQUEST_METHOD'] == "POST"){
        if(isset($_POST['SubmitInfo'])){
            $quantity = $_POST['quantity'];
            $store = $_POST['store'];
            $product_id = $_GET['AddToStore'];
            if(!empty($quantity) && !empty($store)){
                $AddQuery = "INSERT INTO products_quantity (product_id,store_id,min_quantity) values (?,?,?)";
                $stm = $con->prepare($AddQuery);
                $stm->bind_param("iii",$product_id,$store,$quantity);
                $stm->execute();
                header("Location: products.php?AddAnim=1");
            }
            $warnLInfo="Please Enter Valid Info.<br>";
        }
    }


?>



<html>
    <head>
        <link rel="stylesheet" href="css/style.css" />
        <script src="script.js" async></script>
        <title>CarGarage</title>
    </head>

    <body>
        <?php include("header.php");?>
        <div class="provProducts">
            <div class="Title">Edit Info</div>
            <?php $tempProduct_id = $_GET['AddToStore'];
            $result = mysqli_query($con,"SELECT * FROM products WHERE products.id = $tempProduct_id");
            $row = mysqli_fetch_array($result);
            ?>
            <div style="font-size:20px;">Product Name : <?=$row['product_name']?></div>
            <form action="addProductToStore.php?AddToStore=<?=$tempProduct_id?>" method="POST">
                <div class="AddProduct-form">
                    <label for="store">Choose Store</label>
                    <select name="store" id="" class="DeleteP">
                        <optgroup>
                            <?php 
                            $result3 = mysqli_query($con,"SELECT * FROM stores ORDER BY id");
                            while($row3 = mysqli_fetch_array($result3)){ 
                            $tempStore_ID = $row3['id'];
                            $result2 = mysqli_query($con,"SELECT COUNT(id) as total FROM products_quantity where products_quantity.store_id = $tempStore_ID AND products_quantity.product_id = $tempProduct_id ORDER BY id DESC");
                            $row2 = mysqli_fetch_array($result2);
                            if($row2['total']<=0){ ?>
                            <option value="<?=$row3['id']?>"><?=$row3['store_name']?></option>
                            <?php }
                            } ?>
                        </optgroup>
                    </select>
                    <label for="">Minimum Quantity</label>
                    <input type="number" name="quantity">
                    <div class="Warnings-messages"><?=$warnLInfo?></div>
                    <div class="NewProduct-buttons" style="padding-top:10px;">         
                        <button type="submit" name="SubmitInfo" value="">Submit</button>
                        <a href="index.php">Home</a>
                    </div>   
                </div>
            </form>
        </div>
    </body>
</html>