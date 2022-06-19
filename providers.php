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
       if($user_data['property']!='admin'){
            header("Location: index.php");
       }
   }


    if($_SERVER['REQUEST_METHOD']=="POST"){
        if(isset($_POST['deleteP'])){
            $Provider_ID = $_POST['deleteP'];
            $query = "DELETE FROM users WHERE users.user_id = $Provider_ID";
            mysqli_query($con,$query);
            header("Location: providers.php");
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
        <form action="providers.php" method="POST">
            <div class="StoresTile">Providers</div>
                <div class="LocationInfo">
                    <a href="index.php" class="LocationInfo-text LocationInfo-text-buuton">Home</a>
                    <div>/</div>
                    <div>Providers</div>
                </div>
            <div class="StoreList">
                <table class="providersTable">
                    <tr>
                        <th>First Name</th>
                        <th>Second Name</th>
                        <th>Address</th>
                        <th>Phone Number</th>
                        <th>Delete?</th>
                    </tr>
                    <?php $result = mysqli_query($con,"SELECT * FROM users where users.property = 'provider' ORDER BY user_id DESC");
                    while($row = mysqli_fetch_array($result)){ ?>
                    <tr>
                        <td><?=$row['first_name']?></td>
                        <td><?=$row['second_name']?></td>
                        <td><?=$row['email']?></td>
                        <td><?=$row['phone']?></td>
                        <td><button type="submit" name="deleteP" value="<?=$row['user_id']?>">Delete</button></td>
                    </tr>
                    <?php }?>
                </table>
            </div>
        </form>
    </body>
</html>