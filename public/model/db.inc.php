<?php

    try {
        $db = new pdo('mysql:host=localhost; dbname=artty_client; charset=utf8', 'root', '');
    } catch(PDOException $e) {
        die($e->getMessage());
    }

    try {
        $dbA = new pdo('mysql:host=localhost; dbname=artty_appoiments; charset=utf8', 'root', '');
    } catch(PDOException $e) {
        die($e->getMessage());
    }
?>