<?php
    class login {
        public static function isLoggedIn() {
            $userConn  = new userController();

            if (isset($_COOKIE['LGSCCS'])) {
                if ($userConn->getUID(sha1($_COOKIE['LGSCCS'])) != '') {
                    $userid = $userConn->getUID(sha1($_COOKIE['LGSCCS']));

                    if (isset($_COOKIE['LGSCCSBU_'])) {
                        return $userid;
                    } else {
                        if($_COOKIE['LGSCCS']) {
                            setcookie("LGSCCSBU_", '1', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);
                        }
                        return $userid;
                    }
                }
            }

            return false;
        }
    }

    $userCont = new userController();

    if(isset($_POST['loginRequest'])) {
        $requestToken = bin2hex(random_bytes(64));

        $userCont->saveTokenRequest(sha1($requestToken), "artty_appoiments");
        setcookie("TokenRequestCheck", $requestToken, time() + 60 * 10, '/', NULL, NULL, TRUE);
    
        $redirectURI      = "http://localhost/Artty%20Ecosystem/Artty%20Appoiments/Version%200.0.1A/public/checkLogin.php";
        $redirectURICheck = "http://localhost/Artty%20Ecosystem/Artty%20Login/Version%201.0.0/public/login.php?request=" . sha1($requestToken) . "&check=False&redirectURI=" . $redirectURI;

        echo '<script>
            window.location.replace("'. $redirectURICheck .'");
        </script>';
    }

?>