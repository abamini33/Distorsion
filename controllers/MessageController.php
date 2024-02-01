<?php

declare(strict_types=1);
require_once 'models/Category.php';
require_once 'models/Message.php';
require_once 'models/MessageManager.php';
require_once 'models/CategoryManager.php';
require_once 'services/database.php';
require_once 'ControllerInterface.php';

class MessageController implements ControllerInterface
{
    private $message;

    public function __construct()
    {
        $this->message = new Message();
        if (!empty($_POST) && isset($_POST['message'])) {
            $this->handleMessages();
        }
    }
    // La fonction display() est en charge de l'affichage dans chacun des controllers.
    public function display()
    {

        // Getting categories with their respective rooms
        $manager = new CategoryManager();
        $categories = $manager->getCategoriesWithRooms();


        // Getting messages for selected room, if any
        $messages = [];
        if (isset($_GET['room_id'])) {
            $manager = new MessageManager();
            $messages = $manager->getMessages((int)$_GET['room_id']);
        }

        $view = 'views/chat/chat.phtml';
        require './views/layout.phtml';
    }

    public function handleMessages()
    {

        // Handling message creation : vérifie si un message a été soumis et un salon sélectionné.
        if (isset($_GET['room_id'])) {
            $roomId = (int) $_GET['room_id'];
            $content = trim($_POST['message']);

            // Hydrating message : attribuer les valeurs ci-dessus à l'objet Message
            $this->message->setRoomId($roomId);
            $this->message->setContent($content);

            // Detecting errors
            $errors = $this->message->validate();

            // Tableau d'erreurs vide = insertion du nouveau message en base de données et redirection vers le salon choisi
            if (empty($errors)) {

                $manager = new MessageManager();
                if ($manager->insertMessage($this->message)) {
                    header("Location: index.php?page=chat&room_id=$roomId");
                    exit;
                }
            }
        }
    }

    public function togglePin()
    {


        // Récupère l'ID du message à partir de $_GET
        $messageId = (int)($_GET['message_id'] ?? null);
        if (!$messageId) {
            // Gère l'erreur si l'ID n'est pas fourni
            die('ID du message manquant');
        }

        // Récupère le message à partir de l'ID
        $messageManager = new MessageManager();
        $message = $messageManager->getMessageById($messageId);
        if (!$message) {
            // Gère l'erreur si le message n'est pas trouvé
            die('Message introuvable');
        }

        // Basculer l'état épinglé du message
        $message->setPinned(!$message->isPinned());
        $messageManager->togglePinMessage($message->getId(), $message->isPinned());


        // Redirige l'utilisateur vers la page de chat ou là où il se trouvait
        header('Location: index.php?page=chat&room_id=' . $message->getRoomId());
        exit;
    }
}
