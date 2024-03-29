<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $username;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    private $nom;

    #[ORM\Column(type: 'string', length: 255)]
    private $prenom;

    #[ORM\ManyToMany(targetEntity: Projet::class, inversedBy: 'users')]
    private $projet;

    #[ORM\ManyToMany(targetEntity: Tache::class, inversedBy: 'users')]
    private $tache;

    #[ORM\ManyToOne(targetEntity: Projet::class, inversedBy: 'gestionnaires')]
    private $projetgestionnaire;

    #[ORM\Column(type: 'string', length: 255)]
    private $settingtheme;

    #[ORM\Column(type: 'string', length: 255)]
    private $settinginterfacetype;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: MessageSignalementAdmin::class)]
    private $messageSignalementAdmins;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Message::class)]
    private $message;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $confirmationlecturemessage;
    

    public function __construct()
    {
        $this->projet = new ArrayCollection();
        $this->tache = new ArrayCollection();
        $this->messageSignalementAdmins = new ArrayCollection();
        $this->message = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getUsername();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * @return Collection<int, Projet>
     */
    public function getProjet(): Collection
    {
        return $this->projet;
    }

    public function addProjet(Projet $projet): self
    {
        if (!$this->projet->contains($projet)) {
            $this->projet[] = $projet;
        }

        return $this;
    }

    public function removeProjet(Projet $projet): self
    {
        $this->projet->removeElement($projet);

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
        }

        return $this;
    }

    public function removeTache(Tache $tache): self
    {
        $this->tache->removeElement($tache);

        return $this;
    }

    public function getProjetgestionnaire(): ?Projet
    {
        return $this->projetgestionnaire;
    }

    public function setProjetgestionnaire(?Projet $projetgestionnaire): self
    {
        $this->projetgestionnaire = $projetgestionnaire;

        return $this;
    }

    public function getSettingtheme(): ?string
    {
        return $this->settingtheme;
    }

    public function setSettingtheme(string $settingtheme): self
    {
        $this->settingtheme = $settingtheme;

        return $this;
    }

    public function getSettinginterfacetype(): ?string
    {
        return $this->settinginterfacetype;
    }

    public function setSettinginterfacetype(string $settinginterfacetype): self
    {
        $this->settinginterfacetype = $settinginterfacetype;

        return $this;
    }

    /**
     * @return Collection<int, MessageSignalementAdmin>
     */
    public function getMessageSignalementAdmins(): Collection
    {
        return $this->messageSignalementAdmins;
    }

    public function addMessageSignalementAdmin(MessageSignalementAdmin $messageSignalementAdmin): self
    {
        if (!$this->messageSignalementAdmins->contains($messageSignalementAdmin)) {
            $this->messageSignalementAdmins[] = $messageSignalementAdmin;
            $messageSignalementAdmin->setUser($this);
        }

        return $this;
    }

    public function removeMessageSignalementAdmin(MessageSignalementAdmin $messageSignalementAdmin): self
    {
        if ($this->messageSignalementAdmins->removeElement($messageSignalementAdmin)) {
            // set the owning side to null (unless already changed)
            if ($messageSignalementAdmin->getUser() === $this) {
                $messageSignalementAdmin->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessage(): Collection
    {
        return $this->message;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->message->contains($message)) {
            $this->message[] = $message;
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->message->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }

    public function getConfirmationlecturemessage(): ?bool
    {
        return $this->confirmationlecturemessage;
    }

    public function setConfirmationlecturemessage(?bool $confirmationlecturemessage): self
    {
        $this->confirmationlecturemessage = $confirmationlecturemessage;

        return $this;
    }

}
