<?php
session_start();

require "./AuthHelper.php";

$usersFile = 'data/users.csv';
$bannedUsersFile = 'data/banned.csv';

//TODO: REPLACE WITH JWT ON FINAL
if(count($_POST)>0 && isset($_POST['signin']) && isset($_POST['email']) && isset($_POST['password'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    if(AuthHelper::sign_in($email, $password, $usersFile, $bannedUsersFile)) {
        ?>
        <div class="container text-center">
            <h5><?=$email . 'Signed In'?></h5>
        </div>
        <?php
    }
}
if (count($_POST)>0 && isset($_POST['signup']) && isset($_POST['email']) && isset($_POST['password'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    if(AuthHelper::sign_up($usersFile, $bannedUsersFile, $email, $password)) {
        ?>
        <div class="container text-center">
            <h5><?='Welcome ' . $email?></h5>
        </div>
        <?php
    }
}
if (count($_POST)>0 && isset($_POST['signout'])) {
    AuthHelper::sign_out();
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/index.css" />
    <title><?= 'Test' ?></title>
</head>
<body>
<div class="container text-center">
    <?php
    if (isset($_GET['alreadyLogged'])) {
        ?>
        <h5><?='Already logged in'?></h5>
        <?php
    }
    if (isset($_GET['failedSignin'])) {
        ?>
        <h5><?='Failed Signin'?></h5>
        <?php
    }
    ?>
    <div class="form-outline mb-4">
        <h3><a href="./index.php"><?='Back'?></a></h3>
        <form method="POST" action="./test_AuthHelper.php">
            <h6><?='Sign In'?></h6>
            <label for="email"><?='Email'?></label>
            <input type="hidden" name="signin" value="signin"/>
            <input type="email" name="email" />
            <label for="email"><?='Password'?></label>
            <input type="password" name="password" />
            <input type="submit" value="submit" />
        </form>
    </div>
    <br>
    <div class="form-outline mb-4">
        <form method="POST" action="./test_AuthHelper.php">
            <h6><?='Sign Up'?></h6>
            <label for="email"><?='Email'?></label>
            <input type="hidden" name="signup" value="signup"/>
            <input type="email" name="email" />
            <label for="email"><?='Password'?></label>
            <input type="password" name="password" />
            <input type="submit" value="submit"/>
        </form>
    </div>
    <div class="form-outline mb-4">
        <form method="POST" action="./test_AuthHelper.php">
            <h6><?='Sign out'?></h6>
            <input type="hidden" name="signout" value="signout"/>
            <input type="submit" value="submit" />
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>