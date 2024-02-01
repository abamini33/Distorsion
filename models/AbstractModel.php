<?php

declare(strict_types=1);
// Structure de base pour d'autres classes qui auront un identifiant ($id). Ces classes pourront hériter de AbstractModel et utiliser ses méthodes pour manipuler cet identifiant.
abstract class AbstractModel
{
    protected ?int $id = null;

    public function __construct(?int $id = null)
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }
}
