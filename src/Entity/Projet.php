<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjetRepository::class)]
class Projet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $libelle;

    #[ORM\Column(type: 'date')]
    private $datedebut;

    #[ORM\Column(type: 'date', nullable: true)]
    private $datefin;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $budget;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $couts;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'projet')]
    private $users;

    #[ORM\OneToMany(mappedBy: 'projet', targetEntity: Tache::class)]
    private $tache;

    #[ORM\OneToMany(mappedBy: 'projetgestionnaire', targetEntity: User::class)]
    private $gestionnaires;

    #[ORM\OneToMany(mappedBy: 'projet', targetEntity: Message::class)]
    private $messages;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->tache = new ArrayCollection();
        $this->gestionnaires = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getLibelle();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(?\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getBudget(): ?int
    {
        return $this->budget;
    }

    public function setBudget(?int $budget): self
    {
        $this->budget = $budget;

        return $this;
    }

    public function getCouts(): ?int
    {
        return $this->couts;
    }

    public function setCouts(?int $couts): self
    {
        $this->couts = $couts;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addProjet($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeProjet($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Tache>
     */
    public function getTache(): Collection
    {
        return $this->tache;
    }

    public function addTache(Tache $tache): self
    {
        if (!$this->tache->contains($tache)) {
            $this->tache[] = $tache;
            $tache->setProjet($this);
        }

        return $this;
    }

    public function removeTache(Tache $tache): self
    {
        if ($this->tache->removeElement($tache)) {
            // set the owning side to null (unless already changed)
            if ($tache->getProjet() === $this) {
                $tache->setProjet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getGestionnaires(): Collection
    {
        return $this->gestionnaires;
    }

    public function addGestionnaire(User $gestionnaire): self
    {
        if (!$this->gestionnaires->contains($gestionnaire)) {
            $this->gestionnaires[] = $gestionnaire;
            $gestionnaire->setProjetgestionnaire($this);
        }

        return $this;
    }

    public function removeGestionnaire(User $gestionnaire): self
    {
        if ($this->gestionnaires->removeElement($gestionnaire)) {
            // set the owning side to null (unless already changed)
            if ($gestionnaire->getProjetgestionnaire() === $this) {
                $gestionnaire->setProjetgestionnaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setProjet($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getProjet() === $this) {
                $message->setProjet(null);
            }
        }

        return $this;
    }
}
