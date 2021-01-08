<?php

namespace App\Entity;

use App\Repository\TitreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TitreRepository::class)
 * @ORM\Table(name="titre", indexes={@ORM\Index(columns={"nom", "url"}, flags={"fulltext"})})
 */
class Titre
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
     * @ORM\Column(type="string", length=255)
     */
    private $duree;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="titres")
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity=Artiste::class, inversedBy="titres")
     */
    private $artiste;

    /**
     * @ORM\ManyToOne(targetEntity=Album::class, inversedBy="titres")
     */
    private $album;

    /**
     * @ORM\ManyToMany(targetEntity=Playlist::class, mappedBy="titre")
     */
    private $playlists;

    /**
     * @ORM\ManyToMany(targetEntity=Favoris::class, mappedBy="titre")
     */
    private $favoris;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="titres")
     */
    private $fav;

    public function __construct()
    {
        $this->playlists = new ArrayCollection();
        $this->favoris = new ArrayCollection();
        $this->fav = new ArrayCollection();
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

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getArtiste(): ?Artiste
    {
        return $this->artiste;
    }

    public function setArtiste(?Artiste $artiste): self
    {
        $this->artiste = $artiste;

        return $this;
    }

    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    public function setAlbum(?Album $album): self
    {
        $this->album = $album;

        return $this;
    }

    /**
     * @return Collection|Playlist[]
     */
    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }

    public function addPlaylist(Playlist $playlist): self
    {
        if (!$this->playlists->contains($playlist)) {
            $this->playlists[] = $playlist;
            $playlist->addTitre($this);
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): self
    {
        if ($this->playlists->removeElement($playlist)) {
            $playlist->removeTitre($this);
        }

        return $this;
    }

    /**
     * @return Collection|Favoris[]
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Favoris $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris[] = $favori;
            $favori->addTitre($this);
        }

        return $this;
    }

    public function removeFavori(Favoris $favori): self
    {
        if ($this->favoris->removeElement($favori)) {
            $favori->removeTitre($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }

    /**
     * @return Collection|User[]
     */
    public function getFav(): Collection
    {
        return $this->fav;
    }

    public function addFav(User $fav): self
    {
        if (!$this->fav->contains($fav)) {
            $this->fav[] = $fav;
        }

        return $this;
    }

    public function removeFav(User $fav): self
    {
        $this->fav->removeElement($fav);

        return $this;
    }


    public function __toArray()
{
    return [
        'id' => $this->getId(),
        'nom' => $this->name,
        'titre' => $this->titre->toArray()
    ];
}
}
