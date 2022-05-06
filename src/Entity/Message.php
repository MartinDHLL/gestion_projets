<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $corp;

    #[ORM\OneToMany(mappedBy: 'confirmationlecturemessage', targetEntity: User::class)]
    private $confirmationlecture;

    public function __construct()
    {
        $this->confirmationlecture = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCorp(): ?string
    {
        return $this->corp;
    }

    public function setCorp(string $corp): self
    {
        $this->corp = $corp;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getConfirmationlecture(): Collection
    {
        return $this->confirmationlecture;
    }

    public function addConfirmationlecture(User $confirmationlecture): self
    {
        if (!$this->confirmationlecture->contains($confirmationlecture)) {
            $this->confirmationlecture[] = $confirmationlecture;
            $confirmationlecture->setConfirmationlecturemessage($this);
        }

        return $this;
    }

    public function removeConfirmationlecture(User $confirmationlecture): self
    {
        if ($this->confirmationlecture->removeElement($confirmationlecture)) {
            // set the owning side to null (unless already changed)
            if ($confirmationlecture->getConfirmationlecturemessage() === $this) {
                $confirmationlecture->setConfirmationlecturemessage(null);
            }
        }

        return $this;
    }
}
