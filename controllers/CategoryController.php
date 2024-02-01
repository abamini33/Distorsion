<?php

declare(strict_types=1);

require_once 'models/Category.php';
require_once 'models/CategoryManager.php';
require_once 'ControllerInterface.php';

class CategoryController implements ControllerInterface
{
    private $category;
    private $errors = [];

    public function __construct()
    {
        $this->category = new Category();
        // Si le formulaire n'est pas vide, on ajoute la catégorie
        if (!empty($_POST)) {
            $this->addCategory();
        }
    }
    // La fonction display() est en charge de l'affichage dans chacun des controllers.
    public function display()
    {
        $view = 'views/category/add_category.phtml';
        require './views/layout.phtml';
    }
  // Fonction d'ajout d'une catégorie
    public function addCategory()
    {
        if (isset($_POST['name'])) {
            $name = ucfirst(trim($_POST['name']));

            // Hydrating category
            $this->category->setName($name);

            $manager = new CategoryManager();

            //Detecting if not exists
            if ($manager->isCategoryExists($name)) {
                $this->errors[] = 'Cette catégorie existe déjà.';
            } else {
                $this->errors = $this->category->validate();

                // Insertion dans la base de données seulement si le tableau d'erreurs est vide
                if (empty($this->errors)) {
                    if ($manager->insertCategory($name)) {
                        header('Location: index.php?page=chat');
                        exit;
                    } else {
                        $this->errors[] = 'Erreur lors de l\'insertion de la catégorie.';
                    }
                }
            }
        }
    }
}
