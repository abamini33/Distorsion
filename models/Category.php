<?php

declare(strict_types=1);

require_once 'services/database.php';
require_once 'models/AbstractModel.php';

class Category extends AbstractModel
{
    public function __construct(
        private ?string $name = null,
        ?int $id = null
    ) {
        parent::__construct($id);
    }
    // Récupère le nom de la catégorie
    public function getName(): ?string
    {
        return $this->name;
    }
    // Met à jour le nom de la catégorie 
    public function setName(?string $name): void
    {
        $this->name = $name;
    }
    //Retourne un tableau contenant les éventuelles erreurs 
    public function validate(): array
    {
        $errors = [];

        // Checking name length
        if (strlen($this->name) > 60) {
            $errors[] = 'Le nom de la catégorie ne doit pas excéder 60 caractères.';
        }
        if (strlen($this->name) < 1) {
            $errors[] = 'Le nom de la catégorie ne doit pas être vide.';
        }

        return $errors;
    }
}
