<?php

    $loginConn = new userController();

    $loginToken = $_GET['loginStates'];
    $check      = $_GET['check'];

    if($check == 'True') {
        if($loginConn->getLoginToken($loginToken) == sha1($_COOKIE['TokenRequestCheck'])) {
            setcookie("TokenRequestCheck", "", time() - 3600);

            $token = bin2hex(random_bytes(64));

            $loginConn->saveUserToken(sha1($token), $loginToken);

            setcookie("LGSCCS", $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE);
            setcookie("LGSCCSBU_", '1', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);

            echo '<script>
                window.location.replace("index.php");
            </script>';
        }
    }

?>