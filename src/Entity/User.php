<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=UserPypPost::class, mappedBy="user")
     */
    private $userPypPosts;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="user")
     */
    private $posts;

    /**
     * @ORM\OneToOne(targetEntity=UserInfluence::class, mappedBy="user")
     */
    private $userInfluence;

    public function __construct()
    {
        $this->userPypPosts = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $returnRoles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $returnRoles[] = 'ROLE_USER';

        if ('dayall' === $this->getUsername()) {
            $returnRoles[] = 'ROLE_ADMIN';
        }

        return array_unique($returnRoles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUserPypPosts(): Collection
    {
        return $this->userPypPosts;
    }

    public function addUserPypPost(UserPypPost $userPypPost): self
    {
        if (!$this->userPypPosts->contains($userPypPost)) {
            $this->userPypPosts->add($userPypPost);
            if ($userPypPost->getUser() !== $this) {
                $userPypPost->setUser($this);
            }
        }

        return $this;
    }

    public function removeUserPypPost(UserPypPost $userPypPost): self
    {
        if ($this->userPypPosts->contains($userPypPost)) {
            $this->userPypPosts->removeElement($userPypPost);
            if ($userPypPost->getUser() === $this) {
                $userPypPost->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            if ($post->getUser() !== $this) {
                $post->setUser($this);
            }
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    public function getUserInfluence(): ?UserInfluence
    {
        return $this->userInfluence;
    }

    public function setUserInfluence(UserInfluence $userInfluence): self
    {
        $this->userInfluence = $userInfluence;

        return $this;
    }
}
