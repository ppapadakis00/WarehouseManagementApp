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
            $Provider_Fname = $_POST['provider_fname'];
            $Provider_Lname = $_POST['provider_lname'];
            $email = $_POST['address'];
            $password = md5($_POST['password']);
            $Phone_Number = $_POST['phone_number'];
            if(!empty($Provider_Fname) && !empty($Provider_Lname) && !empty($email) && !empty($Phone_Number) && !empty($password)){
                $query = "INSERT INTO users (first_name,second_name,email,phone,password,property) values (?,?,?,?,?,'provider')";
                $stm = $con->prepare($query);
                $stm->bind_param("sssis",$Provider_Fname,$Provider_Lname,$email,$Phone_Number,$password);
                $stm->execute();
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
            <form action="newprovider.php" method="POST" class="AddProduct-form">
                <div class="NewProduct-Title">Add Provider</div>
                <label for="provider_fname">First Name</label>
                <input type="text" name="provider_fname">
                <label for="provider_lname">Last Name</label>
                <input type="text" name="provider_lname">
                <label for="address">Email Address</label>
                <input type="text" name="address">
                <label for="phone_number">Phone Number</label>
                <input type="number" name="phone_number">
                <label for="password" class="labels">Password</label>
                <input type="password" name="password" id="password">
                 <span class="Warnings-messages"><?=$warnInfo?></span>
                <div class="NewProduct-buttons">
                    <button type="submit" name="add">Add</button>
                    <button type="submit" name="return">Return</button>
                </div>
            </form>
        </div>
    </body>
</html>