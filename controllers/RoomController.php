<?php

declare(strict_types=1);

require_once 'models/Category.php';
require_once 'models/CategoryManager.php';
require_once 'models/Room.php';
require_once 'models/RoomManager.php';
require_once 'ControllerInterface.php';

class RoomController implements ControllerInterface
{
    private $category;
    private $room;
    private $errors = [];

    public function __construct()
    {
        $this->category = new Category();
        $this->room = new Room();

        // Si on est dans une requête post, alors on lance la méthode addRoom(). 
        if (!empty($_POST) && isset($_POST['name'])) {
            $this->addRoom();
        }
    }

    // La fonction display() est en charge de l'affichage dans chacun des controllers.
    public function display()
    {

        // Récupérer le nom de la catégorie où l'on ajoute le salon
        if (isset($_GET['category_id'])) {
            $manager = new CategoryManager();
            $category = $manager->getCategoryById((int)$_GET['category_id']);
        }

        // Charger la vue correspondante
        $view = 'views/room/add_room.phtml';
        require './views/layout.phtml';
    }

    // Ajout d'un salon
    public function addRoom()
    {

        if (!isset($_GET['category_id'])) {
            header('Location: index.php?page=chat');
            exit;
        }

        $manager = new CategoryManager();
        $this->category = $manager->getCategoryById((int)$_GET['category_id']);

        if ($this->category === null) {
            header('Location: index.php?page=chat');
            exit;
        }


        $name = ucfirst(trim($_POST['name']));
        $categoryId = (int) $_GET['category_id'];

        // Hydrating room
        $this->room->setName($name);
        $this->room->setCategoryId($categoryId);

        // Detecting errors
        $this->errors = $this->room->validate();


        $manager = new RoomManager();
        if (empty($this->errors)) {
            if (!$manager->isRoomExists($this->room) && $manager->insertRoom($this->room)) {
                header('Location: index.php?page=chat');
                exit;
            } else {
                $this->errors[] = "Un salon avec ce nom existe déjà dans cette catégorie.";
            }
        }
    }
}
