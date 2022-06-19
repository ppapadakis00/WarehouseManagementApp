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
    $classAnimSuc="";
    $classAnimFail="";
    $classAnimInfo="";
    $classAnimCheck="";
    Register();
    Login();

    $suc=0;
    $fail=0;
    $check=0;
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        if(isset($_GET['deleteS'])){
            if($user_data!=0){
                if($user_data['property']=='admin'){
                    $store_id = $_GET['deleteS'];
                    $queryDelete = "DELETE FROM stores WHERE stores.id = $store_id";
                    mysqli_query($con,$queryDelete);
                    $queryDelete = "DELETE FROM products_quantity WHERE products_quantity.store_id = $store_id";
                    mysqli_query($con,$queryDelete);
                    $queryDelete = "DELETE FROM store_sales WHERE store_sales.store_id = $store_id";
                    mysqli_query($con,$queryDelete);
                    header("Location: stores.php");
               }
           }
        }
        if(isset($_GET['autosupply'])){
            if($user_data!=0){
                if($user_data['property']=='admin'){
                    $store_id = $_GET['autosupply'];
                    $results = mysqli_query($con,"SELECT * FROM products_quantity WHERE products_quantity.store_id = $store_id");
                    while($row = mysqli_fetch_array($results)){
                        if($row['min_quantity']>$row['quantity']){
                            $tempQuantityAdd = $row['min_quantity']+($row['min_quantity']-$row['quantity']);
                            $TempProduct_ID = $row['product_id'];
                            $tempPrice=99999999.99;
                            $results2 = mysqli_query($con,"SELECT * FROM products WHERE products.id = $TempProduct_ID");
                            $row2 = mysqli_fetch_array($results2);
                            if($row2['garage_quantity']>=$tempQuantityAdd){
                                $tempQuantityAdd2 = $row['min_quantity']*2;
                                $UpQuery = "UPDATE products_quantity SET products_quantity.quantity = $tempQuantityAdd2 WHERE products_quantity.store_id = $store_id AND products_quantity.product_id = $TempProduct_ID";
                                mysqli_query($con,$UpQuery);
                                $SaleStoreRes = mysqli_query($con,"SELECT COUNT(id) as total FROM store_sales WHERE store_sales.store_id = $store_id");
                                $rowStoreSales = mysqli_fetch_array($SaleStoreRes);
                                if($rowStoreSales['total']==0){
                                    $tempQuanUpdate = $row['min_quantity']+ $row['min_quantity']-$row['quantity'];
                                    $tempPriceSale = $row2['price']*$tempQuantityAdd2;
                                    $SaleStoreAdd = "INSERT INTO store_sales (store_id,quantity,price) values ($store_id,$tempQuanUpdate,$tempPriceSale)";
                                    mysqli_query($con,$SaleStoreAdd);
                                }else{
                                    $SaleStoreRes1 = mysqli_query($con,"SELECT * FROM store_sales WHERE store_sales.store_id = $store_id");
                                    $rowStoreSales1 = mysqli_fetch_array($SaleStoreRes1);
                                    $tempPriceUpdate = $row2['price']*$tempQuantityAdd2 + $rowStoreSales1['price'];
                                    $tempQuanUpdate = $row['min_quantity']+ $row['min_quantity']-$row['quantity'] + $rowStoreSales1['quantity'];
                                    $SaleStoreUpdate = "UPDATE store_sales SET store_sales.quantity = $tempQuanUpdate,store_sales.price =$tempPriceUpdate WHERE store_sales.store_id = $store_id";
                                    mysqli_query($con,$SaleStoreUpdate);
                                }
                                $tempUpQuantity = $row2['garage_quantity']-$tempQuantityAdd;
                                $UpQuery2 = "UPDATE products SET products.garage_quantity = $tempUpQuantity WHERE products.id = $TempProduct_ID";
                                mysqli_query($con,$UpQuery2);
                                $suc++;
                            }else{
                                $CurAddQuan = $row2['garage_quantity']+$row['quantity'];
                                $UpQuery = "UPDATE products_quantity SET products_quantity.quantity=$CurAddQuan WHERE products_quantity.store_id = $store_id AND products_quantity.product_id = $TempProduct_ID";
                                mysqli_query($con,$UpQuery);
                                $SaleStoreRes = mysqli_query($con,"SELECT COUNT(id) as total FROM store_sales WHERE store_sales.store_id = $store_id");
                                $rowStoreSales = mysqli_fetch_array($SaleStoreRes);
                                if($rowStoreSales['total']==0){
                                    $tempPriceSale = $row2['price']*$CurAddQuan;
                                    $tempQuanUpdate = $row2['garage_quantity'];
                                    $SaleStoreAdd = "INSERT INTO store_sales (store_id,quantity,price) values ($store_id,$tempQuanUpdate,$tempPriceSale)";
                                    mysqli_query($con,$SaleStoreAdd);
                                }else{
                                    $SaleStoreRes1 = mysqli_query($con,"SELECT * FROM store_sales WHERE store_sales.store_id = $store_id");
                                    $rowStoreSales1 = mysqli_fetch_array($SaleStoreRes1);
                                    $tempPriceUpdate = $row2['price']*$row2['garage_quantity'] + $rowStoreSales1['price'];
                                    $tempQuanUpdate = $row2['garage_quantity'] + $rowStoreSales1['quantity'];
                                    $SaleStoreUpdate = "UPDATE store_sales SET store_sales.quantity = $tempQuanUpdate,store_sales.price = $tempPriceUpdate WHERE store_sales.store_id = $store_id";
                                    mysqli_query($con,$SaleStoreUpdate);
                                }
                                $tempQuantityAdd = $tempQuantityAdd-$row2['garage_quantity'];
                                $tempUpQuantity = 0;
                                $UpQuery2 = "UPDATE products SET products.garage_quantity = $tempUpQuantity WHERE products.id = $TempProduct_ID";
                                mysqli_query($con,$UpQuery2);
                                $resultsCheck = mysqli_query($con,"SELECT COUNT(id) as total FROM provider_products WHERE provider_products.product_id = $TempProduct_ID");
                                $rowCheck = mysqli_fetch_array($resultsCheck);
                                if($rowCheck['total']>0){
                                    $results1 = mysqli_query($con,"SELECT * FROM provider_products WHERE provider_products.product_id = $TempProduct_ID");
                                    while($row1 = mysqli_fetch_array($results1)){
                                        if($tempPrice > $row1['price']){
                                            $tempPrice=$row1['price'];
                                            $tempProv_ID = $row1['provider_id'];
                                        }
                                    }
                                    if($tempPrice!=99999999.99){
                                        $SendQuery = "INSERT INTO ordersto (product_id,provider_id,quantity) values ($TempProduct_ID,$tempProv_ID,$tempQuantityAdd)";
                                        mysqli_query($con,$SendQuery);
                                    }
                                    $fail++;
                                }else{
                                    $check++;
                                }
                            }
                            $tempDate = date("Y-m-d H:i:s");
                            $DateQuery = "INSERT INTO supply_orders (date) values ('$tempDate')";
                            mysqli_query($con,$DateQuery);
                        }
                    }
                    if($check>0){
                        $classAnimCheck="statusAnimClass";
                    }else{
                        if($fail>0){
                            $classAnimFail="statusAnimClass";
                        }else{
                            if($suc>0){
                                $classAnimSuc="statusAnimClass";
                            }else{
                                $classAnimInfo="statusAnimClass";
                            }
                        }
                    }
               }
           }
        }
    }

?>



<html>
    <head>
        <link rel="stylesheet" href="css/style.css" />
        <script src="script.js" async></script>
        <script src="continue.js" defer></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js" defer></script>
    </head>

    <body>
        <?php include("header.php");?>
        <?php if(isset($_GET['store_select'])){
            $store_id = $_GET['store_select'];?>
            <?php $result = mysqli_query($con,"SELECT * FROM stores WHERE stores.id = $store_id");
                while($row = mysqli_fetch_array($result)){ ?>
                <div class="StoresTile"><?=$row['store_name']?></div>
                <div class="LocationInfo">
                    <a href="index.php" class="LocationInfo-text LocationInfo-text-buuton">Home</a>
                    <div>/</div>
                    <a href="stores.php" class="LocationInfo-text LocationInfo-text-buuton">Stores</a>
                    <div>/</div>
                    <div><?=$row['store_name']?></div>
                </div>
                <div class="StoreList">
                    <div class="SelectedProduct">
                        <img class="Images" src="data:Images/jpg;charset=utf8;base64,<?php echo base64_encode($row['store_image']); ?>" alt="">
                        <div class="StoreCard-Name Quantity-text">Quantity</div>
                        <div class="Quantity-info">
                            <div>Products</div>
                            <table class="StoresQuantity-box">
                                <tr>
                                    <th>Products</th>
                                    <th>Quantity</th>
                                </tr>
                                <?php $result1 = mysqli_query($con,"SELECT * FROM products_quantity WHERE products_quantity.store_id = $store_id");
                                while($row1 = mysqli_fetch_array($result1)){ 
                                    $product_id=$row1['product_id'];?>
                                <?php $result2 = mysqli_query($con,"SELECT * FROM products WHERE products.id = $product_id");
                                $row2 = mysqli_fetch_array($result2) ?>
                                <tr>
                                    <td><?=$row2['product_name']?></td>
                                    <td><?=$row1['quantity']?></td>
                                </tr>
                                <?php }?>
                            </table>
                        </div>
                </div>
                </div>
                <?php }?>
        <?php }else{?>
        <form action="stores.php" method="GET">
            <div class="StoresTile">Stores</div>
            <div class="LocationInfo">
                        <a href="index.php" class="LocationInfo-text LocationInfo-text-buuton">Home</a>
                        <div>/</div>
                        <div>Stores</div>
                        <?php if($user_data!=0){
                 if($user_data['property']=="admin"){
                    ?>
                        <div class="Continue-button reportButton" onClick = StoreReport();>Stores Report</div>
                        <?php }
                        }?>
                    </div>
            <div class="StoreList">
                <?php $result = mysqli_query($con,"SELECT * FROM stores ORDER BY id DESC");
                while($row = mysqli_fetch_array($result)){ ?>
                <button class="StoreCard" type="submit" name="store_select" value="<?=$row['id']?>">
                    <img class="Images" src="data:Images/jpg;charset=utf8;base64,<?php echo base64_encode($row['store_image']); ?>" alt="">
                    <div class="StoreCard-Name"><?=$row['store_name']?></div>
                    <?php if($user_data!=0){
                    if($user_data['property']=="admin"){ ?>
                    <div class="ProductsButtons">
                        <a href="stores.php?autosupply=<?=$row['id']?>" class="DeleteP">Auto Supply</a>
                        <a href="stores.php?deleteS=<?=$row['id']?>" class="DeleteP">Delete</a>
                    </div>
                    <?php }
                    } ?>
                </button>
                <?php }?>
            </div>
        </form>
        <div style="width:100%; display:flex; align-items:center; justify-content:center;">
            <div class="supplySucStatus <?=$classAnimSuc?>">
                <div>Success Supply!!</div>
                <img src="./Images/success.png" style="width:40px;height:40px;" alt="">
            </div>
            <div class="supplyFailStatus <?=$classAnimFail?>">
                <div>Awaiting Provider!!</div>
                <img src="./Images/wrong.jpg" style="width:40px;height:40px;" alt="">
            </div>
            <div class="supplyInfoStatus <?=$classAnimInfo?>">
                <div>Already Supplied!!</div>
                <img src="./Images/info.png" style="width:40px;height:40px;" alt="">
            </div>
            <div class="supplyCheckStatus <?=$classAnimCheck?>">
                <div>No Provider For Some Products!!</div>
                <img src="./Images/warning.png" style="width:40px;height:40px;" alt="">
            </div>
        </div>
        <?php }?>
        <div id="RemReport">
            
        </div>
    </body>
</html>