<?php

class database
{
    private $host             = 'localhost';
    private $user             = 'root';
    private $pwd              = '';
    private $dbNameClient     = 'client';
    private $dbNameAppoiments = 'appoiments';
    private $dbNameBusiness   = 'business';

    protected function connect_client()
    {
        $connection = "mysql:host=" . $this->host . ';dbname=' . $this->dbNameClient;
        $pdo        = new PDO($connection, $this->user, $this->pwd);

        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;
    }

    protected function connect_appoiments()
    {
        $connection = "mysql:host=" . $this->host . ';dbname=' . $this->dbNameAppoiments;
        $pdo        = new PDO($connection, $this->user, $this->pwd);

        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;
    }

    protected function connect_business()
    {
        $connection = "mysql:host=" . $this->host . ';dbname=' . $this->dbNameBusiness;
        $pdo        = new PDO($connection, $this->user, $this->pwd);

        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;
    }

    protected function searchDatabase()
    {
        try {
            $db = new pdo('mysql:host=' . $this->host . '; dbname=' . $this->dbNameClient . '; charset=utf8', $this->user, $this->pwd);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}
