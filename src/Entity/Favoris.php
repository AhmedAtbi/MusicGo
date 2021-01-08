<?php

namespace App\Entity;

use App\Repository\FavorisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FavorisRepository::class)
 */
class Favoris
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Titre::class, inversedBy="favoris")
     */
    private $titre;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     */
    private $utilisateur;

    public function __construct()
    {
        $this->titre = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Titre[]
     */
    public function getTitre(): Collection
    {
        return $this->titre;
    }

    public function addTitre(Titre $titre): self
    {
        if (!$this->titre->contains($titre)) {
            $this->titre[] = $titre;
        }

        return $this;
    }

    public function removeTitre(Titre $titre): self
    {
        $this->titre->removeElement($titre);

        return $this;
    }

    public function getUtilisateur(): ?User
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?User $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
