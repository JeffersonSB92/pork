<?php

namespace App\Models;

use PDO;

class CategoriesModel
{
    private $pdo;

    public function __construct(PDO $pdo) 
    {
        $this->pdo = $pdo;
    }

    public function getAllCategories()
    {
        $sql = 'SELECT * FROM public.category';
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryById(int $category_id)
    {
        $sql = 'SELECT * FROM public.category WHERE id = :category_id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createCategory(string $title)
    {
        $sql = 'INSERT INTO public.category (title) VALUES (:title)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->execute();
    }
    
    // public function updateCategory()
    // public function deleteCategory()
}