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
        if(isset($_POST['add'])){
            $image_name = $_FILES['filename']['name'];
            $Store_name = $_POST['store_name'];
            $Address = $_POST['address'];
            if(!empty($Store_name) && !empty($Address) && !empty($image_name)){
                $FileName = $_FILES['filename']['name'];
                $TmpName = $_FILES['filename']['tmp_name'];
                $imgContent = addslashes(file_get_contents($TmpName)); 

                $query = "INSERT INTO stores (store_name,address,store_image) values ('$Store_name','$Address','$imgContent')";
                mysqli_query($con,$query);
                unset($_POST['add']);
                header("Location: index.php");
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
            <form action="newstore.php" method="POST" class="AddProduct-form" enctype="multipart/form-data">
                <div class="NewProduct-Title">Add Store</div>
                <label for="filename">Store Image</label>
                <input type="file" name="filename" id="filename">
                <label for="store_name">Store Name</label>
                <input type="text" name="store_name">
                <label for="address">Address</label>
                <input type="text" name="address">
                 <span class="Warnings-messages"><?=$warnInfo?></span>
                <div class="NewProduct-buttons">
                    <button type="submit" name="add">Add</button>
                    <button type="submit" name="return">Return</button>
                </div>
            </form>
        </div>
    </body>
</html>