<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $surname = null;

    #[ORM\Column(type: 'string', length: 255)]
    private $prenom;

    #[ORM\Column(type: 'string', length: 255)]
    private $nom;

    #[ORM\Column(length: 20)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $creerCompte;


    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $photo;

    #[ORM\OneToOne(inversedBy: 'client', cascade: ['persist', 'remove'])]
    private ?User $userAccount = null;

    /**
     * @var Collection<int, Dette>
     */
    #[ORM\OneToMany(targetEntity: Dette::class, mappedBy: 'client')]
    private Collection $dettes;

    #[ORM\Column(nullable: true)]
    private ?float $montant_dette = null;

    public function __construct()
    {
        $this->dettes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }


    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getUserAccount(): ?User
    {
        return $this->userAccount;
    }

    public function setUserAccount(?User $userAccount): static
    {
        $this->userAccount = $userAccount;

        return $this;
    }
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;
        return $this;
    }
    
    public function getCreerCompte(): ?bool
    {
        return $this->creerCompte;
    }

    public function setCreerCompte(?bool $creerCompte): self
    {
        $this->creerCompte = $creerCompte;
        return $this;
    }

    /**
     * @return Collection<int, Dette>
     */
    public function getDettes(): Collection
    {
        return $this->dettes;
    }

    public function addDette(Dette $dette): static
    {
        if (!$this->dettes->contains($dette)) {
            $this->dettes->add($dette);
            $dette->setClient($this);
        }

        return $this;
    }

    public function removeDette(Dette $dette): static
    {
        if ($this->dettes->removeElement($dette)) {
            // set the owning side to null (unless already changed)
            if ($dette->getClient() === $this) {
                $dette->setClient(null);
            }
        }

        return $this;
    }

    public function getMontantDette(): ?float
    {
        return $this->getTotalDettes();
    }

    public function setMontentDette(?float $montent_due): static
    {
        $this->montant_dette = $montent_dette;

        return $this;
    }
   

    public function getTotalDettes(): float
    {
        // Récupérer le total des dettes associées
        $totalDettes = array_reduce($this->dettes->toArray(), function ($sum, Dette $dette) {
            return $sum + $dette->getMontant();
        }, 0.0);
    
        // Ajouter montant_dette, uniquement si celui-ci est supérieur à 0
        return $this->montant_dette > 0 ? $this->montant_dette + $totalDettes : $totalDettes;
    }

public function getMontantVerse(): float
{
    return array_reduce($this->dettes->toArray(), function ($sum, $dette) {
        return $sum + $dette->getMontantVerse();
    }, 0.0);
}

public function getMontantRestant(): float
{
    return $this->getTotalDettes() - $this->getMontantVerse();
}
public function updateMontantDette(): void
{
    $this->montant_dette = $this->getTotalDettes();
}

}
