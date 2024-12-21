<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $login = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\OneToOne(mappedBy: 'userAccount', cascade: ['persist', 'remove'])]
    private ?Client $client = null;

    #[ORM\Column(type: 'json')]
    private array $roles = []; // Ajout d'une propriété pour les rôles

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        if ($client === null && $this->client !== null) {
            $this->client->setUserAccount(null);
        }

        if ($client !== null && $client->getUserAccount() !== $this) {
            $client->setUserAccount($this);
        }

        $this->client = $client;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->login; // Utilisé pour l'authentification
    }

    public function getRoles(): array
    {
        // Assurez-vous de renvoyer toujours au moins un rôle
        return $this->roles ?: ['ROLE_USER'];
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getSalt(): ?string
    {
        return null; // Pas nécessaire si vous utilisez un encodeur moderne
    }

    public function eraseCredentials(): void
    {
        // Effacer les informations sensibles si nécessaire
    }
}
