<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     *
     * @MaxDepth(10)
     *
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Group", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $group;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="post", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $reactions;

    /**
     * @ORM\OneToMany(targetEntity="Image", mappedBy="post", cascade={"persist"})
     */
    private $images;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getReactions()
    {
        return $this->reactions;
    }

    public function setReactions($reactions): self
    {
        $this->reactions = $reactions;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setGroup(?Group $group): self
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removecomment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    /**
     * Gets triggered only on insert
     * @ORM\PrePersist
     */
    public function updatedTimestamps() : void
    {
        $this->updatedAt = new \DateTime("now");
        if($this->createdAt === null)
        {
            $this->createdAt = new \DateTime("now");
        }
    }

    /**
     * @return mixed
     */
    public function getImages()
    {
        return $this->images;
    }

    public function setImages($images): self
    {
        $this->images[] = $images;
        return $this;
    }

    public function isUserLikePost(User $user)
    {
        foreach ($this->reactions as $reaction)
        {
            if($reaction['user'] === $user->getId())
            {
                return true;
            }
        }
        return false;
    }

    public function addReaction(User $user, $reaction) : self
    {
        $this->reactions[] = ['user' => $user->getId(), 'reaction' => $reaction];

        return $this;
    }

    public function removeReaction(User $user, $reaction) : self
    {
        foreach ($this->reactions as $key => $rowReaction)
        {
            if($rowReaction['user'] == $user->getId() && $rowReaction['reaction'] == $reaction)
            {
                unset($this->reactions[$key]);
            }
        }

        return $this;
    }
}
