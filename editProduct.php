<?php   
ob_start();
session_start();
    include("Setup.php");
    include("functions.php");

    $user_data = check_login($con);
    $warnEmail="";
    $warnPass="";
    $warnInfo="";  
    $warnLInfo="";
    if($user_data==0){
        header("Location: index.php");
   }else{
       if($user_data['property']!='provider'){
            header("Location: index.php");
       }
   }
   $user_ID = $user_data['user_id'];

   if(!isset($_POST['editInfo'])){
        header("Location: provider_products.php");
    }

   if($_SERVER['REQUEST_METHOD'] == "POST"){
        if(isset($_POST['SubmitInfo'])){
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];
            $PoviderProducts_ID = $_POST['editInfo'];
            $query = "UPDATE provider_products SET provider_products.price=$price,provider_products.quantity=$quantity WHERE provider_products.id = $PoviderProducts_ID";
            mysqli_query($con,$query);
            header("Location: provider_products.php");
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
            <?php $TempPoviderProducts_ID = $_POST['editInfo'];
            $results = mysqli_query($con,"SELECT * FROM provider_products WHERE provider_products.id = $TempPoviderProducts_ID");
            $row1 = mysqli_fetch_array($results);
            $tempProduct_ID = $row1['product_id'];
            $results1 = mysqli_query($con,"SELECT * FROM products WHERE products.id = $tempProduct_ID");
            $row2 = mysqli_fetch_array($results1);
            ?>
            <div style="font-size:20px;">Product Name : <?=$row2['product_name']?></div>
            <form action="editProduct.php" method="POST">
                <div class="AddProduct-form">
                    <label for="price">Price</label>
                    <input type="number" step="0.01" name="price" value="<?=$row1['price']?>" required>
                    <label for="quantity">Quantity</label>
                    <input type="number" name="quantity" value="<?=$row1['quantity']?>" required>
                    <input type="number" name="editInfo" value="<?=$_POST['editInfo']?>" hidden>     
                    <div class="NewProduct-buttons" style="padding-top:10px;">         
                        <button type="submit" name="SubmitInfo" value="<?=$_POST['editInfo']?>">Submit</button>
                    </div>   
                </div>
            </form>
        </div>
    </body>
</html>