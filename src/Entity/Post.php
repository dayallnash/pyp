<?php

namespace App\Entity;

use App\Repository\PostRepository;
use DateTimeInterface;
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
     * @ORM\OneToMany(targetEntity=UserPypPost::class, mappedBy="post", orphanRemoval=true, cascade={"persist"})
     */
    private $userPypPosts;

    /**
     * @ORM\Column(type="integer")
     */
    private $userId;

    /**
     * @ORM\OneToMany(targetEntity=Interaction::class, mappedBy="post", orphanRemoval=true)
     */
    private $interactions;

    /**
     * @ORM\OneToMany(targetEntity=Report::class, mappedBy="post", cascade={"persist"})
     */
    private $reports;

    public function __construct()
    {
        $this->userPypPosts = new ArrayCollection();
        $this->interactions = new ArrayCollection();
        $this->reports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatetimePosted(): ?DateTimeInterface
    {
        return $this->datetime_posted;
    }

    public function setDatetimePosted(DateTimeInterface $datetime_posted): self
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
     * @return Collection|UserPypPost[]
     */
    public function getUserPypPosts(): Collection
    {
        return $this->userPypPosts;
    }

    public function addUserPypPost(UserPypPost $userPypPost): self
    {
        if (!$this->userPypPosts->contains($userPypPost)) {
            $this->userPypPosts[] = $userPypPost;
            $userPypPost->setPost($this);
        }

        return $this;
    }

    public function removeUserPypPost(UserPypPost $userPypPost): self
    {
        if ($this->userPypPosts->removeElement($userPypPost)) {
            // set the owning side to null (unless already changed)
            if ($userPypPost->getPost() === $this) {
                $userPypPost->setPost(null);
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

    /**
     * @return Collection|Interaction[]
     */
    public function getInteractions(): Collection
    {
        return $this->interactions;
    }

    public function addInteraction(Interaction $interaction): self
    {
        if (!$this->interactions->contains($interaction)) {
            $this->interactions[] = $interaction;
            $interaction->setPost($this);
        }

        return $this;
    }

    public function removeInteraction(Interaction $interaction): self
    {
        if ($this->interactions->removeElement($interaction)) {
            // set the owning side to null (unless already changed)
            if ($interaction->getPost() === $this) {
                $interaction->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Report[]
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports[] = $report;
            $report->setPost($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getPost() === $this) {
                $report->setPost(null);
            }
        }

        return $this;
    }
}
