<?php
include('model/autoload.php');
include('systems/login.php');

if (login::isLoggedIn()) {
    echo 'Logged in';
} else {
    echo 'Not logged in';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form method="post">
        <input type="submit" name="loginRequest" value="Enter in your account">
    </form>
</body>

</html>