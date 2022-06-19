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
    Register();
    Login();

?>



<html>
    <head>
        <link rel="stylesheet" href="css/style.css" />
        <script src="script.js" async></script>
        <script src="continue2.js" defer></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js" defer></script>
        <title>CarGarage</title>
    </head>

    <body>
        <?php include("header.php");?>
        <?php if($user_data!=0){
                 if($user_data['property']=="admin"){
                    ?>
        <div class="Continue-button reportButton" style="width:100px;" onClick = BasicReport();>Total Report</div>
        <?php }
        }?>
        <div class="LabelsMain">
            <div class="Stores">
                <div>
                    <div class="StoreName-Title LabelsTitle">Stores</div>
                    <?php $result = mysqli_query($con,"SELECT * FROM stores ORDER BY id DESC LIMIT 2");
                    while($row = mysqli_fetch_array($result)){ 
                    ?>
                    <div class="card">
                        <img class="Images" src="data:Images/jpg;charset=utf8;base64,<?php echo base64_encode($row['store_image']); ?>" alt="">
                        <img class="IgnoreImages" src="Images/New.png" alt="">
                        <div class="StoreName-text"><?=$row['store_name']?></div>
                    </div>
                    <?php }?>
                </div>
            </div>
            <div class="Stores">
                <div class="StoreName-Title LabelsTitle">Products</div>
                <?php $result = mysqli_query($con,"SELECT * FROM products ORDER BY id DESC LIMIT 4");
                while($row = mysqli_fetch_array($result)){ 
                ?>
                <div class="card">
                    <img class="Images" src="data:Images/jpg;charset=utf8;base64,<?php echo base64_encode($row['product_image']); ?>" alt="">
                    <img class="IgnoreImages" src="Images/New.png" alt="">
                    <div class="StoreName-text"><?=$row['product_name']?></div>
                </div>
                <?php }?>
            </div>
            <div></div>
        </div>
        <div id="DeleteBasicRep">
            
        </div>
    </body>
</html>