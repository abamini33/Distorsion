<?php

require_once 'models/AbstractManager.php';
require_once 'services/database.php';

class RoomManager extends AbstractManager
{
    // Ajoute un salon
    public function insertRoom(Room $room): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO rooms (category_id, name) VALUES (:category_id, :name)');
        $stmt->bindValue(':category_id', $room->getCategoryId());
        $stmt->bindValue(':name', $room->getName());
        return $stmt->execute();
    }
    // Vérifie qu'un salon existe
    public function isRoomExists(Room $room): bool
    {
        $stmt = $this->pdo->prepare('
            SELECT rooms.id FROM rooms
            WHERE rooms.name = :name AND rooms.category_id = :category_id
        ');
        $stmt->bindValue(':name', $room->getName());
        $stmt->bindValue(':category_id', $room->getCategoryId());
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    // Récupère les salons par catégorie
    public function getRoomsByCategoryId(int $categoryId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM rooms WHERE rooms.category_id = :category_id');
        $stmt->bindValue(':category_id', $categoryId);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
