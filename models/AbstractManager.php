<?php

require_once 'services/database.php';

class AbstractManager
{
    protected \PDO $pdo;
    
    public function __construct()
    {
        $this->pdo = getConnection();
    }
    
}
