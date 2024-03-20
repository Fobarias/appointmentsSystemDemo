<?php

    $userConn   = new userController();

    $userid     = $userConn->getUID(sha1($_COOKIE['LGSCCS']));
    $subEnd     = $userConn->getEndingSub($userid);

    $getCurDate = date("Ymd");

    if($userid != '') {
        if($getCurDate > $subEnd) {
            echo '<script>
                window.location.replace("../sub-ended.php");
            </script>';
        } 
    }

?>