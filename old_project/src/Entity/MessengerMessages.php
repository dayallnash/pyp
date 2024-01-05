<?php

namespace App\Entity;

use App\Repository\MessengerMessagesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessengerMessagesRepository::class)
 */
class MessengerMessages
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $body;

    /**
     * @ORM\Column(type="text")
     */
    private $headers;

    /**
     * @ORM\Column(type="string", length=190)
     */
    private $queueName;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $availableAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deliveredAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getHeaders(): ?string
    {
        return $this->headers;
    }

    public function setHeaders(string $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    public function getQueueName(): ?string
    {
        return $this->queueName;
    }

    public function setQueueName(string $queueName): self
    {
        $this->queueName = $queueName;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAvailableAt(): ?\DateTimeInterface
    {
        return $this->availableAt;
    }

    public function setAvailableAt(\DateTimeInterface $availableAt): self
    {
        $this->availableAt = $availableAt;

        return $this;
    }

    public function getDeliveredAt(): ?\DateTimeInterface
    {
        return $this->deliveredAt;
    }

    public function setDeliveredAt(?\DateTimeInterface $deliveredAt): self
    {
        $this->deliveredAt = $deliveredAt;

        return $this;
    }
}
