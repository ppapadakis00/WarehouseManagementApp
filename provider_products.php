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

   if($_SERVER['REQUEST_METHOD'] == "POST"){
        if(isset($_POST['deleteMP'])){
            $pro_pro_id = $_POST['deleteMP'];
            $DeleteQuery = "DELETE FROM provider_products WHERE provider_products.id = $pro_pro_id";
            mysqli_query($con,$DeleteQuery);
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
            <div class="Title">My Products</div>
                <table class="MyProductsTable">
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Delete?</th>
                        <th>Edit?</th>
                    </tr>
                    <?php $query = "SELECT * FROM provider_products WHERE provider_products.provider_id = $user_ID";
                    $results = mysqli_query($con,$query);
                    while($row = mysqli_fetch_array($results)){
                        $proProduct_ID = $row['product_id'];
                        $query1 = "SELECT * FROM products WHERE products.id = $proProduct_ID";
                        $results1 = mysqli_query($con,$query1);
                        while($row1 = mysqli_fetch_array($results1)){ ?>
                    <tr>
                        <td><?=$row1['product_name']?></td>
                        <td><?=$row['price']?>€</td>
                        <td><?=$row['quantity']?></td>
                        <td>
                            <form action="provider_products.php" method="POST" style="margin:0;">
                                <button type="submit" name="deleteMP" value="<?=$row['id']?>" class="deleteP">Delete</button>
                            </form>
                        </td>
                        <td>
                            <form action="editProduct.php" method="POST" style="margin:0;">
                                <button type="submit" name="editInfo" value="<?=$row['id']?>" class="deleteP">Edit</button>
                            </form>
                        </td>
                    </tr>
                    <?php }
                    } ?>
                </table>
        </div>
    </body>
</html>