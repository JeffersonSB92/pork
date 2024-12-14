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
        $sql = "SELECT * FROM public.transactions";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTransactionsByCategory(int $categoryId): ?array 
    {
        $sql = 'SELECT * FROM public.transactions WHERE category_id = :categoryId';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam('categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTransactionsBySubcategory(int $subcategoryId): ?array 
    {
        $sql = 'SELECT * FROM public.transactions WHERE subcategory_id = :subcategoryId';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam('subcategoryId', $subcategoryId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTransactionsByType(string $type): ?array 
    {
        $sql = 'SELECT * FROM public.transactions WHERE type = :type';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam('type', $type);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTransactionsByDate(string $date): ?array 
    {
        $sql = 'SELECT * FROM public.transactions WHERE date = :date';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam('date', $date);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createTransaction(int $userId, int $categoryId, int $subcategoryId, string $title, float $value, string $type, string $date) 
    {
        $sql = '
        INSERT INTO public. transactions(user_id, category_id, subcategory_id, title, value, type, date, created_at)
        VALUES (:userId, :categoryId, :subcategoryId, :title, :value, :type, :date, now())
        ';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam('userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam('categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->bindParam('subcategoryId', $subcategoryId, PDO::PARAM_INT);
        $stmt->bindParam('title', $title);
        $stmt->bindParam('value', $value);
        $stmt->bindParam('type', $type);
        $stmt->bindParam('date', $date);
        $stmt->execute();
    }

    public function updateTransaction(array $fields, int $transactionId): bool
    {
        $sql = 'UPDATE public.transactions SET ';

        $setClauses = [];
        $params = [':id' => $transactionId];

        foreach ($fields as $field => $value) {
            $setClauses[] = "$field = :$field";
            $params[":$field"] = $value;
        }

        $sql .= implode(', ', $setClauses);
        $sql .= " WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($params);
    }

    public function deleteTransaction(int $transactionId)
    {
        $sql = 'DELETE FROM public.transactions WHERE id = :transactionId';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':transactionId', $transactionId, PDO::PARAM_INT);
        $stmt->execute();
    }
    
}