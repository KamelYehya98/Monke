<?php
if(isset($_POST['submit_signin_btn']))
{
    require 'dbhandler.php';

    $username = $_POST['username'];
    $password = $_POST['password1'];
    if(empty($username) || empty($password))
    {
        header("Location: ./index.php?error=emptyfields");
        exit();
    }
    else
    {
        $sql = "SELECT * FROM users WHERE username=? OR email=?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql))
        {
            header("Location: ./index.php?error=sqlerror");
            exit();
        }
        else
        {
            mysqli_stmt_bind_param($stmt, "ss", $username, $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if($row = mysqli_fetch_assoc($result))
            {
                if(password_verify($password, $row['pass']) == false)
                {
                    header("Location: ./index.php?error=wrongpassword&username=".$username);
                    exit();
                }
                else if(password_verify($password, $row['pass']) == true)
                {
                    session_start();
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['store'] = true;
                    header("Location: ./score.php?login=success");
                    exit();
                }
                else{
                    header("Location: ./index.php?error=wrongpassword&username=".$username);
                    exit();
                }
            }
            else
            {
                header("Location: ./index.php?error=wrongusername");
                exit();
            }
        }
    }
}
else
{
    header("Location: ./index.php?error=akelkhara");
    exit();
}



