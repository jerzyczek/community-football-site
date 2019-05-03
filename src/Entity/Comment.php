<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CommentReplies", mappedBy="comment", orphanRemoval=true)
     */
    private $commentReplies;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $reactions;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $this->commentReplies = new ArrayCollection();
        $this->reactions = [];
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getReactions()
    {
        return $this->reactions;
    }

    public function setReactions($reactions): self
    {
        $this->reactions = $reactions;

        return $this;
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

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

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

    /**
     * @return Collection|CommentReplies[]
     */
    public function getCommentReplies(): Collection
    {
        return $this->commentReplies;
    }

    public function addCommentReply(CommentReplies $commentReply): self
    {
        if (!$this->commentReplies->contains($commentReply)) {
            $this->commentReplies[] = $commentReply;
            $commentReply->setComment($this);
        }

        return $this;
    }

    public function removeCommentReply(CommentReplies $commentReply): self
    {
        if ($this->commentReplies->contains($commentReply)) {
            $this->commentReplies->removeElement($commentReply);
            // set the owning side to null (unless already changed)
            if ($commentReply->getComment() === $this) {
                $commentReply->setComment(null);
            }
        }

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

    public function isUserLikeComment(User $user)
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

    public function getTimeAgo()
    {

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
}
