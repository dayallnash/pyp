<?php

namespace App\Entity;

use App\Repository\UserInfluenceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserInfluenceRepository::class)
 */
class UserInfluence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $influence;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="userInfluence")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInfluence(): ?int
    {
        return $this->influence;
    }

    public function setInfluence(int $influence): self
    {
        $this->influence = $influence;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
