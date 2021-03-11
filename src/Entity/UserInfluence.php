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
    private $userId;

    /**
     * @ORM\Column(type="integer")
     */
    private $influence;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
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
}
