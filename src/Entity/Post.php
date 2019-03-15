<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Group", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $groupId;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="postId", orphanRemoval=true)
     */
    private $commments;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $reactions;




    public function __construct()
    {
        $this->commments = new ArrayCollection();
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

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getGroupId(): ?Group
    {
        return $this->groupId;
    }

    public function setGroupId(?Group $groupId): self
    {
        $this->groupId = $groupId;

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

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getUpdatedDate(): ?\DateTimeInterface
    {
        return $this->updatedDate;
    }

    public function setUpdatedDate(?\DateTimeInterface $updatedDate): self
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getCommments(): Collection
    {
        return $this->commments;
    }

    public function addCommment(Comment $commment): self
    {
        if (!$this->commments->contains($commment)) {
            $this->commments[] = $commment;
            $commment->setPostId($this);
        }

        return $this;
    }

    public function removeCommment(Comment $commment): self
    {
        if ($this->commments->contains($commment)) {
            $this->commments->removeElement($commment);
            // set the owning side to null (unless already changed)
            if ($commment->getPostId() === $this) {
                $commment->setPostId(null);
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
