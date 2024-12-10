<?php

namespace App\Models;

use PDO;

class TransactionsModel
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllTransactions()
    {
        $stmt = $this->pdo->query("SELECT * FROM public.transactions");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}