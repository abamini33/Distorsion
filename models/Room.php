<?php

declare(strict_types=1);

require_once 'models/AbstractModel.php';

class Room extends AbstractModel
{
    public function __construct(
        private ?int $categoryId = null,
        private ?string $name = null,
        ?int $id = null
    ) {
        parent::__construct($id);
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(?int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function validate(): array
    {
        $errors = [];

        // Checking name length
        if (strlen($this->name) > 60) {
            $errors[] = 'Le nom du salon ne doit pas excéder 60 caractères.';
        }
        if (strlen($this->name) < 1) {
            $errors[] = 'Le nom du salon ne doit pas être vide.';
        }

        return $errors;
    }
}
