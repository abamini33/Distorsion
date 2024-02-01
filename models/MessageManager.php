<?php

require_once 'services/database.php';
require_once 'Room.php';
require_once 'models/AbstractManager.php';



class MessageManager extends AbstractManager
{
    // Obtenir tous les messages existants
    public function getMessages(int $roomId): array
    {
        $stmt = $this->pdo->prepare('
            SELECT * FROM messages
            WHERE messages.room_id = :room_id
            ORDER BY pinned DESC, created_at DESC, id DESC
            LIMIT 200
        ');
        $stmt->bindValue(':room_id', $roomId);
        $stmt->execute();
        $messages = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $messages[] = new Message(
                (int) $row['room_id'],
                $row['content'],
                (bool) $row['pinned'],
                new \DateTime($row['created_at']),
                (int) $row['id']
            );
        }
        return $messages;
    }

    //Insérer les nouveaux messages
    public function insertMessage(Message $message): bool
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO messages (content, room_id)
            VALUES (:content, :room_id)
        ');
        $stmt->bindValue(':content', $message->getContent());
        $stmt->bindValue(':room_id', $message->getRoomId());
        return $stmt->execute();
    }

    public function getMessageById(int $messageId): ?Message
    {
        $stmt = $this->pdo->prepare('
        SELECT * FROM messages
        WHERE id = :message_id
        LIMIT 1
    ');
        $stmt->bindValue(':message_id', $messageId);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($row) {
            return new Message(
                (int) $row['room_id'],
                $row['content'],
                (bool) $row['pinned'],
                new \DateTime($row['created_at']),
                (int) $row['id']
            );
        }

        return null;
    }


    // Epingler, désépingler un message
    public function togglePinMessage(int $messageId, bool $isPinned): bool
    {
        $stmt = $this->pdo->prepare('UPDATE messages SET pinned = :pinned WHERE id = :id');
        $stmt->bindValue(':pinned', $isPinned, \PDO::PARAM_BOOL);
        $stmt->bindValue(':id', $messageId, \PDO::PARAM_INT);
        return $stmt->execute();
    }
}
