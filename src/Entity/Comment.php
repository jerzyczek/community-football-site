<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="commments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $postId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userId;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CommentReplies", mappedBy="commentId", orphanRemoval=true)
     */
    private $commentReplies;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $reactions;

    public function __construct()
    {
        $this->commentReplies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostId(): ?Post
    {
        return $this->postId;
    }

    public function setPostId(?Post $postId): self
    {
        $this->postId = $postId;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

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

    /**
     * @return Collection|CommentReplies[]
     */
    public function getCommentReplies(): Collection
    {
        return $this->commentReplies;
    }

    public function addCommentReplies(CommentReplies $commentsReply): self
    {
        if (!$this->commentReplies->contains($commentsReply)) {
            $this->commentReplies[] = $commentsReply;
            $commentsReply->setCommentId($this);
        }

        return $this;
    }

    public function removeCommentReplies(CommentReplies $commentsReply): self
    {
        if ($this->commentReplies->contains($commentsReply)) {
            $this->commentReplies->removeElement($commentsReply);
            // set the owning side to null (unless already changed)
            if ($commentsReply->getCommentId() === $this) {
                $commentsReply->setCommentId(null);
            }
        }

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
}
