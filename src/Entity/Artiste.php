<?php

namespace App\Entity;

use App\Repository\ArtisteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArtisteRepository::class)
 * @ORM\Table(name="artiste", indexes={@ORM\Index(columns={"nom", "pays"}, flags={"fulltext"})})
 */
class Artiste
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pays;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\OneToMany(targetEntity=Album::class, mappedBy="artiste")
     */
    private $albums;

    /**
     * @ORM\OneToMany(targetEntity=Titre::class, mappedBy="artiste")
     */
    private $titres;

    public function __construct()
    {
        $this->albums = new ArrayCollection();
        $this->titres = new ArrayCollection();
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

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): self
    {
        $this->pays = $pays;

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

    /**
     * @return Collection|Album[]
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    public function addAlbum(Album $album): self
    {
        if (!$this->albums->contains($album)) {
            $this->albums[] = $album;
            $album->setArtiste($this);
        }

        return $this;
    }

    public function removeAlbum(Album $album): self
    {
        if ($this->albums->removeElement($album)) {
            // set the owning side to null (unless already changed)
            if ($album->getArtiste() === $this) {
                $album->setArtiste(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Titre[]
     */
    public function getTitres(): Collection
    {
        return $this->titres;
    }

    public function addTitre(Titre $titre): self
    {
        if (!$this->titres->contains($titre)) {
            $this->titres[] = $titre;
            $titre->setArtiste($this);
        }

        return $this;
    }

    public function removeTitre(Titre $titre): self
    {
        if ($this->titres->removeElement($titre)) {
            // set the owning side to null (unless already changed)
            if ($titre->getArtiste() === $this) {
                $titre->setArtiste(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }
}
