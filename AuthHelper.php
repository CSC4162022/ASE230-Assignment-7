<?php

class AuthHelper
{
    static function sign_up($usersFile, $bannedUsersFile, $email, $password)
    {

        // add the body of the function based on the guidelines of signup.php
        if (!self::validateSigninInput($email, $password)) {
            return false;
        }
        if (self::userIsBanned($email, $bannedUsersFile)) {
            return false;
        }
        // check if the email is in the database already
        if (self::emailExists($email, $usersFile)) {

            header('Location: ./test_AuthHelper.php');
        } else {
            // encrypt password
            // save the user in the database
            self::saveNewUser($usersFile, $email, self::encryptPassword($password));
            $_SESSION['newEnrolledUser'] = true;
            return true;
        }
        return false;
    }

    static function sign_out()
    {
        $_SESSION['logged']=false;
        session_destroy();
        header('Location: ./test_AuthHelper.php');
    }

    static function sign_in($email, $password, $usersFile, $bannedUsers )
    {
        if(!file_exists($usersFile) || !file_exists($bannedUsers)) return false;
        if(self::userIsBanned($email, $bannedUsers)) return false;
        if(isset($email) && isset($password)) {
            $usersLine = fopen($usersFile, 'r');  //open for reading
            while( false !== ( $data = fgetcsv($usersLine))) {
                if($data[0] == $email) {
                    $verify = password_verify($password, $data[1]);
                    if ($verify) {
                        $_SESSION['email'] = $email;
                        $_SESSION['password'] = $password;
                        $_SESSION['logged']=true;
                        return true;
                    }
                }
            }
            if (AuthHelper::is_logged()) {
                header('Location: ./test_AuthHelper.php?alreadyLogged=true');
            }
            else {
                $_SESSION['logged'] = false;
                header('Location: ./test_AuthHelper.php?failedSignin=true');
            }
        }
        return false;
    }

    static function signin($email, $password, $usersFile, $bannedUsersFile){
        print_r('called');
        /*
        if(!file_exists($usersFile) || !file_exists($bannedUsersFile)) return false;
        if (self::userIsBanned($email, $bannedUsersFile) == true) die('You are banned');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            print_r('Signin EMAIL INVALID');
            return false;
        }
        //password doesn't contain at least 2 symbols or isn't long enough
        else if (!preg_match('/[\'^£$%&*!()}{@#~?><>,|=_+¬-]/', $password) || strlen($password) < 8)
        {
            print_r('Signin EMAIL INVALID');
            return false;
        }

        $usersLine = fopen($usersFile, 'r');  //open for reading
        while( false !== ( $data = fgetcsv($usersLine))) {
            if($data[0] == $email && password_verify($password, $data[1])) {
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                $_SESSION['logged']=true;
                return true;
            }
        }
        return false;
        */
    }

    static function emailExists($email, $usersFile) {

        $usersLine = fopen($usersFile, 'r');  //open for reading
        while( false !== ( $data = fgetcsv($usersLine) ) ) {
            if($data[0] == $email) {
                return true;
            }
        }
        return false;
    }

    static function saveNewUser($usersFile, $email, $passwordHash) {
        $file = new SplFileObject($usersFile, 'a');
        $file->fputcsv(array($email, $passwordHash));
        $file = null;
    }


    static function encryptPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    static function is_logged()
    {
        if (isset($_SESSION['logged']) && $_SESSION['logged']==true) {
            return true;
        }
        return false;
    }

    //validate the email and password
    static function validateSigninInput($email, $password) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            print_r('Signin EMAIL INVALID');
            return false;
        }
        //password doesn't contain at least 2 symbols or isn't long enough
        else if (!preg_match('/[\'^£$%&*!()}{@#~?><>,|=_+¬-]/', $password) || strlen($password) < 8)
        {
            print_r('Signin EMAIL INVALID');
            return false;
        }
        return true;
    }

    static function userIsBanned($email, $bannedUsersFile)
    {
        $bannedUsersLine = fopen($bannedUsersFile, 'r');
        while (false !== ($data = fgetcsv($bannedUsersLine))) {
            if ($data[0] == $email) {
                return true;
            }
        }
        return false;
    }

    //check if the file containing banned users exists, check if the email has been banned
    //check if the file containing users exists, check if the email is registered
    static function verifyUser($usersFile, $bannedUsersFile, $email) {
        $emailMatch = false;
        if(file_exists($usersFile) && file_exists($bannedUsersFile)) {

            $usersLine = fopen($usersFile, 'r');  //open for reading
            while( false !== ( $data = fgetcsv($usersLine) ) ) {
                if($data[0] == $email) {
                    $emailMatch = true;
                }
            }
            if (self::userIsBanned($email, $bannedUsersFile) == true) {
                die('You are banned');
            }
            if ($emailMatch == true) {
                return true;
            }
        }
        return false;
    }

    static function verifyPassword($password, $email, $usersFile) {

        $usersLine = fopen($usersFile, 'r');  //open for reading
        while( false !== ( $data = fgetcsv($usersLine) ) ) {
            if($data[0] == $email) {
                $verify = password_verify($password, $data[1]);
                if ($verify) {
                    return true;
                }
            }
        }
        return false;
    }
}