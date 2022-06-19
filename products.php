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
    $classAnimDel="";
    $classAnimAdd="";
    Register();
    Login();

    if($_SERVER['REQUEST_METHOD'] == "GET"){
        if(isset($_GET['deletedAnim'])){
            if($_GET['deletedAnim']==1){
                $classAnimDel="statusAnimClass";
            }
        }
        if(isset($_GET['AddAnim'])){
            if($_GET['AddAnim']==1){
                $classAnimAdd="statusAnimClass";
            }
        }
        if(isset($_GET['deleteP'])){
            if($user_data!=0){
                if($user_data['property']=='admin'){
                    $store_id = $_GET['deleteP'];
                    $queryDelete = "DELETE FROM products WHERE products.id = $store_id";
                    mysqli_query($con,$queryDelete);
                    $queryDelete = "DELETE FROM products_quantity WHERE products_quantity.product_id = $store_id";
                    mysqli_query($con,$queryDelete);
                    $queryDelete = "DELETE FROM provider_products WHERE provider_products.product_id = $store_id";
                    mysqli_query($con,$queryDelete);
                    $queryDelete = "DELETE FROM ordersto WHERE ordersto.product_id = $store_id";
                    mysqli_query($con,$queryDelete);
                    header("Location: products.php?deletedAnim=1");
               }
           }
        }
        if(isset($_GET['AddProduct'])){
            if($user_data!=0){
                if($user_data['property']=='provider'){
                    $provider_id = $user_data['user_id'];
                    $product_id = $_GET['AddProduct'];
                    $queryAdd = "INSERT INTO provider_products (product_id,provider_id,price,quantity) values ($product_id,$provider_id,0,0)";
                    mysqli_query($con,$queryAdd);
                    header("Location: products.php");
               }
           }
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
        <?php if(isset($_GET['product_select'])){
            $product_id = $_GET['product_select'];?>
            <?php $result = mysqli_query($con,"SELECT * FROM products WHERE products.id = $product_id");
                while($row = mysqli_fetch_array($result)){ ?>
                <div class="StoresTile"><?=$row['product_name']?></div>
                <div class="LocationInfo">
                    <a href="index.php" class="LocationInfo-text LocationInfo-text-buuton">Home</a>
                    <div>/</div>
                    <a href="products.php" class="LocationInfo-text LocationInfo-text-buuton">Products</a>
                    <div>/</div>
                    <div><?=$row['product_name']?></div>
                </div>
                <div class="StoreList">
                    <div class="SelectedProduct">
                        <img class="Images" src="data:Images/jpg;charset=utf8;base64,<?php echo base64_encode($row['product_image']); ?>" alt="">
                        <div class="StoreCard-Name Quantity-text">Quantity</div>
                        <div class="Quantity-info">
                            <div>Stores</div>
                            <table class="StoresQuantity-box">
                                <tr>
                                    <th>Store</th>
                                    <th>Quantity</th>
                                    <th>Min Quantity</th>
                                </tr>
                                <?php $result1 = mysqli_query($con,"SELECT * FROM products_quantity WHERE products_quantity.product_id = $product_id");
                                while($row1 = mysqli_fetch_array($result1)){ 
                                    $store_id=$row1['store_id'];?>
                                <?php $result2 = mysqli_query($con,"SELECT * FROM stores WHERE stores.id = $store_id");
                                $row2 = mysqli_fetch_array($result2) ?>
                                <tr>
                                    <td><?=$row2['store_name']?></td>
                                    <td><?=$row1['quantity']?></td>
                                    <td><?=$row1['min_quantity']?></td>
                                </tr>
                                <?php }?>
                            </table>
                            <div>Garage</div>
                            <table class="StoresQuantity-box">
                                <tr>
                                    <th>Garage</th>
                                    <th>Quantity</th>
                                </tr>
                                <tr>
                                    <td>WareHouse</td>
                                    <td><?=$row['garage_quantity']?></td>
                                </tr>
                            </table>
                        </div>
                </div>
                </div>
                <?php }?>
        <?php }else{?>
        <form action="products.php" method="GET">
            <div class="StoresTile">Products</div>
                <div class="LocationInfo">
                    <a href="index.php" class="LocationInfo-text LocationInfo-text-buuton">Home</a>
                    <div>/</div>
                    <div>Products</div>
                </div>
            <div class="StoreList">
                <?php $result = mysqli_query($con,"SELECT * FROM products ORDER BY id DESC");
                while($row = mysqli_fetch_array($result)){ ?>
                <button class="StoreCard" type="submit" name="product_select" value="<?=$row['id']?>">
                    <img class="Images" src="data:Images/jpg;charset=utf8;base64,<?php echo base64_encode($row['product_image']); ?>" alt="">
                    <div class="StoreCard-Name"><?=$row['product_name']?></div>
                    <?php if($user_data!=0){
                    if($user_data['property']=="admin"){ ?>
                    <div class="ProductsButtons">
                        <a href="addProductToStore.php?AddToStore=<?=$row['id']?>" class="DeleteP">AddToStore</a>
                        <a href="products.php?deleteP=<?=$row['id']?>" class="DeleteP">Delete</a>
                    </div>
                    <?php }else if($user_data['property']=="provider"){ ?>
                    <?php $provider_ID = $user_data['user_id'];
                    $product_ID = $row['id'];
                    $queryCheckPro = "SELECT COUNT(id) as total FROM provider_products WHERE provider_products.product_id = $product_ID AND provider_products.provider_id = $provider_ID";
                    $resultTotal = mysqli_query($con,$queryCheckPro);
                    $rowResults = mysqli_fetch_array($resultTotal);
                    if($rowResults['total']<=0){ ?>
                    <div class="ProductsButtons">
                        <a href="products.php?AddProduct=<?=$row['id']?>" class="DeleteP">AddProduct</a>
                    </div>
                    <?php }else{ ?>
                    <div class="ProductsButtons">
                        <div class="DeleteP">Added</div>
                    </div>
                    <?php }
                    }
                    } ?>
                </button>
                <?php }?>
            </div>
        </form>
        <?php }?>
        <div style="width:100%; display:flex; align-items:center; justify-content:center;">
            <div class="supplySucStatus <?=$classAnimAdd?>">
                <div>Success Add!!</div>
                <img src="./Images/success.png" style="width:40px;height:40px;" alt="">
            </div>
            <div class="supplyFailStatus <?=$classAnimDel?>">
                <div>Deleted!!</div>
                <img src="./Images/wrong.jpg" style="width:40px;height:40px;" alt="">
            </div>
        </div>
    </body>
</html>