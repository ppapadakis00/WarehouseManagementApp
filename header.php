<html>
<div class="Title">Car Garage</div>
        <div class="navBar">
            <a href="index.php">Home</a>
            <div class="dropdown" data-dropdown>
                <button class="links" data-dropdown-button="">Stores</button>
                <div class="dropdown-menu-right grid-content">
                    <div>
                        <a href="stores.php" class="StoreCategory-text">Stores</a><br><br>
                        <!-- <div class="dropdown-header"><?=$row['category_name']?></div> -->
                        <div class="dropdown-links">
                            <?php $result1 = mysqli_query($con,"SELECT * FROM stores ORDER BY id DESC");
                            while($row1 = mysqli_fetch_array($result1)){ ?>
                            <form action="stores.php" method="get" class="dropdownForm">
                                <button type="submit" class="StoreCategory-Buttons" name="store_select" value="<?=$row1['id'];?>"><?=$row1['store_name'];?></button>
                            </form>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dropdown" data-dropdown>
                <button class="links" data-dropdown-button="">Products</button>
                <div class="dropdown-menu-right grid-content">
                    <div>
                        <a href="products.php" class="StoreCategory-text">Products</a><br><br>
                        <!-- <div class="dropdown-header"><?=$row['category_name']?></div> -->
                        <div class="dropdown-links">
                            <?php $result1 = mysqli_query($con,"SELECT * FROM products ORDER BY id DESC");
                            while($row1 = mysqli_fetch_array($result1)){ ?>
                            <form action="products.php" method="GET" class="dropdownForm">
                                <button type="submit" class="StoreCategory-Buttons" name="product_select" value="<?=$row1['id'];?>"><?=$row1['product_name'];?></button>
                            </form>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
            <?php if($user_data!=0){
                 if($user_data['property']=="admin"){
                    ?>
            <a href="providers.php">Providers</a>
            <a href="newproduct.php">AddProduct</a>
            <a href="newstore.php">AddStore</a>
            <a href="newprovider.php">AddProvider</a>
            <a href="order.php">Order</a>
            <a href="supply.php">Supply</a>
            <?php }
            }?>
            <?php if($user_data!=0){
                if($user_data['property']=="provider"){
                    $TempUser_ID = $user_data['user_id'];
                    ?>
                    <a href="provider_products.php">My Products</a>
                    <a href="orderrequest.php">
                        <div>Order Requests</div>
                        <?php $result = mysqli_query($con,"SELECT COUNT(id) as total FROM ordersto WHERE ordersto.provider_id = $TempUser_ID ORDER BY id");
                        $row = mysqli_fetch_array($result); 
                        if($row['total']>0){ ?>
                        <div class="RequestIndicator"><?=$row['total']?></div>
                        <?php } ?>
                    </a>
                <?php }
            }?>
            <div class="Login">
                <?php if($user_data==0){ ?>
                <div>Welcome, Guest</div>
                <div class="dropdown" data-dropdown>
                    <button class="links" data-dropdown-button="">Login</button>
                    <div class="dropdown-menu loginMenu">
                        <form method="post" class="formLogin">
                            <label for="email" class="labels">Email</label>
                            <input type="email" name="email" id="email">
                            <label for="password" class="labels">Password</label>
                            <input type="password" name="password" id="password">
                            <label class="Warnings-messages"><?=$warnLInfo?></label>
                            <button type="submit" name="login">Login</button>
                        </form>
                    </div>
                </div>
                <?php }else{ ?>
                <div class="welcome-name">Welcome, <?=$user_data['first_name'];?> <?=$user_data['second_name'];?></div>
                <a href="logout.php" class="Logout-Button">Logout</a>
                <?php if($user_data!=0){
                 if($user_data['property']=="admin"){
                    ?>
                <div class="dropdown" data-dropdown>
                    <button class="links" data-dropdown-button="">New Member</button>
                    <div class="dropdown-menu loginMenu">
                        <form method="post" class="formLogin">
                            <label for="first_name" class="labels">First Name</label>
                            <input type="text" name="first_name" id="first_name">
                            <label for="second_name" class="labels">Second Name</label>
                            <input type="text" name="second_name" id="second_name">
                            <label class="Warnings-messages"><?=$warnInfo?></label>
                            <label for="email" class="labels">Email</label>
                            <input type="email" name="email" id="email">
                            <label class="Warnings-messages"><?=$warnEmail?></label>
                            <label for="phone_number">Phone Number</label>
                            <input type="number" name="phone_number">
                            <label for="password" class="labels">Password</label>
                            <input type="password" name="password" id="password">
                            <label class="Warnings-messages"><?=$warnPass?></label>
                            <label for="Rpassword" class="labels">Repeat Password</label>
                            <input type="password" name="Rpassword" id="Rpassword">
                            <button type="submit" class="LoginButton" name="register">Submit</button>
                        </form>
                    </div>
                </div>
                <?php }
            }?>
                <?php } ?>
            </div>
        </div>
</html>