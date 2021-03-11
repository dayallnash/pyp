<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datetime_posted;

    /**
     * @ORM\Column(type="text")
     */
    private $post_content;

    /**
     * @ORM\OneToMany(targetEntity=UserPipePost::class, mappedBy="post", orphanRemoval=true)
     */
    private $userPipePosts;

    /**
     * @ORM\Column(type="integer")
     */
    private $userId;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->userPipePosts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatetimePosted(): ?\DateTimeInterface
    {
        return $this->datetime_posted;
    }

    public function setDatetimePosted(\DateTimeInterface $datetime_posted): self
    {
        $this->datetime_posted = $datetime_posted;

        return $this;
    }

    public function getPostContent(): ?string
    {
        return $this->post_content;
    }

    public function setPostContent(string $post_content): self
    {
        $this->post_content = $post_content;

        return $this;
    }

    /**
     * @return Collection|UserPipePost[]
     */
    public function getUserPipePosts(): Collection
    {
        return $this->userPipePosts;
    }

    public function addUserPipePost(UserPipePost $userPipePost): self
    {
        if (!$this->userPipePosts->contains($userPipePost)) {
            $this->userPipePosts[] = $userPipePost;
            $userPipePost->setPost($this);
        }

        return $this;
    }

    public function removeUserPipePost(UserPipePost $userPipePost): self
    {
        if ($this->userPipePosts->removeElement($userPipePost)) {
            // set the owning side to null (unless already changed)
            if ($userPipePost->getPost() === $this) {
                $userPipePost->setPost(null);
            }
        }

        return $this;
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
}
