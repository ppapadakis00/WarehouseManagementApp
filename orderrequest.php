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

   $User_ID = $user_data['user_id'];


    if($_SERVER['REQUEST_METHOD']=="POST"){
        if(isset($_POST['accept'])){
            $product_ID = $_POST['accept'];
            $quantity = $_POST['quantity'];
            $order_id = $_POST['order_id'];

            $resultOwnedNum = mysqli_query($con,"SELECT COUNT(id) as total FROM provider_products where provider_products.provider_id = $User_ID AND provider_products.product_id=$product_ID ORDER BY id DESC");
            $rowOwnedNum = mysqli_fetch_array($resultOwnedNum);

            if($rowOwnedNum['total']>0){
                $resultQuanOwned = mysqli_query($con,"SELECT * FROM provider_products where provider_products.provider_id = $User_ID AND provider_products.product_id=$product_ID ORDER BY id DESC");
                $rowQuanOwned = mysqli_fetch_array($resultQuanOwned);

                if($rowQuanOwned['quantity']>=$quantity){
                    $resultQuan = mysqli_query($con,"SELECT * FROM products where products.id = $product_ID ORDER BY id DESC");
                    $rowQuan = mysqli_fetch_array($resultQuan);
                    $tempProviderQuantity = $rowQuanOwned['quantity']-$quantity;
                    $tempQuantity = $rowQuan['garage_quantity']+$quantity;
                    $tempPricePro = $rowQuanOwned['price'];
                    $query = "UPDATE products SET products.garage_quantity=$tempQuantity,products.price = $tempPricePro WHERE products.id = $product_ID";
                    mysqli_query($con,$query);
                    $query1 = "UPDATE provider_products SET provider_products.quantity=$tempProviderQuantity WHERE provider_products.product_id=$product_ID AND provider_products.provider_id=$User_ID";
                    mysqli_query($con,$query1);

                    $query1 = "DELETE FROM ordersto WHERE ordersto.id = $order_id";
                    mysqli_query($con,$query1);
                    //header("Location: providers.php");
                    $tempPrice = $rowQuanOwned['price'];
                    $date = date("Y-m-d H:i:s");
                    $successQuery = "INSERT INTO success_order (product_id,quantity,price,date) values ($product_ID,$quantity,$tempPrice,'$date')";
                    mysqli_query($con,$successQuery);
                }else{
                    $warnInfo="Not Enough Quantity Left.<br>"; 
                }
            }else{
                $warnInfo="Connot Find This Product.<br>"; 
            }
        }
        if(isset($_POST['decline'])){
            $order_id=$_POST['decline'];
            $query1 = "DELETE FROM ordersto WHERE ordersto.id = $order_id";
            mysqli_query($con,$query1);
        }
    }

?>



<html>
    <head>
        <link rel="stylesheet" href="css/style.css" />
        <script src="script.js" async></script>
    </head>

    <body>
        <?php include("header.php");?>
            <div class="StoresTile">Orders Requests</div>
            <div class="StoreList">
                <table class="providersTable">
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Answer?</th>
                    </tr>
                    <?php $result = mysqli_query($con,"SELECT * FROM ordersto where ordersto.provider_id = $User_ID ORDER BY id DESC");
                    while($row = mysqli_fetch_array($result)){
                        
                    $tempProduct_ID = $row['product_id'];
                    $result1 = mysqli_query($con,"SELECT * FROM products where products.id = $tempProduct_ID ORDER BY id DESC");
                    $row1 = mysqli_fetch_array($result1);?>
                    <form action="orderrequest.php" method="POST" style="margin:0;">
                    <tr>
                        <td><?=$row1['product_name']?></td>
                        <td><?=$row['quantity']?></td>
                        <td>
                            <input type="number" name="quantity" value="<?=$row['quantity']?>" hidden>
                            <input type="number" name="order_id" value="<?=$row['id']?>" hidden>
                            <button type="submit" name="accept" value="<?=$row['product_id']?>">Accept?</button>
                            <button type="submit" name="decline" value="<?=$row['id']?>">Decline?</button>
                            <span class="Warnings-messages"><?=$warnInfo?></span>
                        </td>
                    </tr>
                    </form>
                    <?php }?>
                </table>
            </div>
    </body>
</html>