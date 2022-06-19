<?php
function check_login($con)
{

    if(isset($_SESSION['id']))
    {
        $id = $_SESSION['id'];//id = User_id
        $query = "select * from users where user_id = '$id' limit 1";//Dhmiourgoume to QUERY

        $results = mysqli_query($con,$query);//ekteloume to QUERY
        if($results && mysqli_num_rows($results)>0)
        {
            $user_data = mysqli_fetch_assoc($results);
            return $user_data;//Epistrefoume twn xrhsth
        }
    }else
    {
        return 0;
    }

    //redirect to login
    //header("Location: login.php");
    die;
}

function Register(){
    global $warnEmail,$warnPass,$warnInfo;
    include("Setup.php");
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        if(isset($_POST['register'])){
            //somrthing was posted
            $first_name = $_POST['first_name'];
            $second_name = $_POST['second_name'];
            $phone = $_POST['phone_number'];
            $pattern = "/[A-Z]{1}[a-z0-9]*/";
            if(!preg_match($pattern, $_POST['password']))
            {
                $warnPass= "Invalid Password.<br>";
            }else{
                $password = md5($_POST['password']);
                $Rpassword = md5($_POST['Rpassword']);
                $email = $_POST['email'];
        
                if(!empty($first_name) && !empty($second_name) && !empty($password) && !empty($Rpassword) && !empty($email) && !empty($phone))
                {
                    if($password == $Rpassword){
                        $query = mysqli_query($con,"SELECT COUNT(user_id) AS p FROM users WHERE users.email='$email'");
                        $results = mysqli_fetch_array($query);
                        $row = $results['p'];
            
                        if($row==0){
                            $query = "insert into users (first_name,second_name,password,email,phone,property) values ('$first_name','$second_name','$password','$email',$phone,'admin')";
                            mysqli_query($con,$query);
            
                            header("Location: index.php");
                        }
                        $warnEmail = "Emaill already Exists.<br>";
                    }
                    $warnPass= "Password does not Match.<br>";
                }else
                {
                    $warnInfo = "Please Enter Valid Info<br>";
                }
            }
        }
    }
    unset($_POST['register']);
}

function Login(){
    include("Setup.php");
    global $warnLInfo;
    $warnLInfo="";
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        if(isset($_POST['login'])){
            $email = $_POST['email'];
            $password = md5($_POST['password']);
            if(!empty($email) && !empty($password))
            {
                $query = "select * from users where email = '$email' limit 1";
                $results = mysqli_query($con,$query);
                
                if($results)
                {
                    if($results && mysqli_num_rows($results)>0)
                    {
                        $user_data = mysqli_fetch_assoc($results);
                                
                        if($user_data['password'] == $password)
                        {
                            $_SESSION['id'] = $user_data['user_id'];
                            header("Location: index.php");
                            die;
                        }
                    }
                }
                $warnLInfo = "Wrong Email Or Password.";
            }else
            {
                $warnLInfo = "Wrong Email Or Password.";
            }
        }
    }
}