<?php

require_once 'services/database.php';
require_once 'models/AbstractManager.php';
require_once 'Category.php';
require_once 'Room.php';

class CategoryManager extends AbstractManager
{
    //Insère les catégories dans la BDD
    public function insertCategory(string $name): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO categories (name) VALUES (:name)');
        $stmt->bindValue(':name', $name);
        return $stmt->execute();
    }

    //Récupère les catégories en un tableau associatif
    public function getCategories()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM categories');
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getCategoryById(int $categoryId) : ?Category
    {
        $stmt = $this->pdo->prepare('SELECT * FROM categories WHERE categories.id = :category_id');
        $stmt->bindValue(':category_id', $categoryId);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return null;
        }
        else{
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            $category = new Category($result['name'], $result['id']);
            return $category;
        }

    }

    // Vérifie si un nom de catégorie existe déjà
    public function isCategoryExists(string $name): bool
    {
        $stmt = $this->pdo->prepare('SELECT categories.id FROM categories WHERE categories.name = :name');
        $stmt->bindValue(':name', $name);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Récupère les catégories et leurs salons respectifs
    public function getCategoriesWithRooms(): array
    {
        $stmt = $this->pdo->prepare('
            SELECT
                rooms.id AS room_id, rooms.name AS room_name,
                categories.id AS category_id, categories.name AS category_name
            FROM categories
            LEFT JOIN rooms ON rooms.category_id = categories.id
            ORDER BY LOWER(categories.name) ASC, LOWER(rooms.name) ASC
        ');
        $stmt->execute();

        $categories = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            if (!isset($categories[$row['category_id']])) {
                $categories[$row['category_id']]['category'] = new Category(
                    $row['category_name'],
                    (int) $row['category_id']
                );
            }
            if ($row['room_id'] !== null) {
                $categories[$row['category_id']]['rooms'][] = new Room(
                    (int) $row['category_id'],
                    $row['room_name'],
                    (int) $row['room_id']
                );
            }
        }

        return $categories;
    }
}
