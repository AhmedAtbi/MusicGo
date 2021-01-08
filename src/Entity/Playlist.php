<?php

namespace App\Entity;

use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlaylistRepository::class)
 */
class Playlist
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToMany(targetEntity=Titre::class, inversedBy="playlists")
     */
    private $titre;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="playlists")
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
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

    public function __toString()
    {
        return $this->nom;
    }
}
