<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChatRepository")
 */
class Chat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $user1;

    /**
     * @ORM\Column(type="integer")
     */
    private $user2;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $messages = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="chats")
     */
    private $members;

    public function __construct()
    {
        $this->members = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser1(): ?int
    {
        return $this->user1;
    }

    public function setUser1(int $user1): self
    {
        $this->user1 = $user1;

        return $this;
    }

    public function getUser2(): ?int
    {
        return $this->user2;
    }

    public function setUser2(int $user2): self
    {
        $this->user2 = $user2;

        return $this;
    }

    public function getMessages(): ?array
    {
        return $this->messages;
    }


    public function setMessages(?array $message): self
    {
        $this->messages = $message;

        return $this;
    }


    public function addMessage($userId, $message): self //jakiś obiekt tu można zrobić
    {

        $this->messages[] = [
            'user'    => $userId,
            'message' => $message,
            'date'    => (new \DateTime())->format("Y-m-d H:i:s")
        ];

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(User $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
        }

        return $this;
    }

    public function removeMember(User $member): self
    {
        if ($this->members->contains($member)) {
            $this->members->removeElement($member);
        }

        return $this;
    }
}
