<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Classe (le "moule") : décrit ce qu'est un Player.
 * Une instance (un objet concret) = un joueur précis en mémoire, souvent une ligne en base.
 */
#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    // Propriétés en private : l'état de l'objet n'est modifié que via les méthodes (get / set) = encapsulation
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column]
    private ?int $age = null;

    #[ORM\ManyToOne(inversedBy: 'players')]
    private ?Team $Team = null;

    // $this désigne "cet objet Player" sur lequel on appelle la méthode
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this; // retourner $this permet d'enchaîner : $p->setName('')->setAge(20);
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->Team;
    }

    public function setTeam(?Team $Team): static
    {
        $this->Team = $Team;

        return $this;
    }
}
