<?php

namespace App\Entity;

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
            'user' => $userId,
            'message' => $message,
            'date' => (new \DateTime())->format("Y-m-d H:i:s")
        ];

        return $this;
    }
}
