<?php

namespace App\Controller;
use App\Form\SearchAnnonceType;

use App\Repository\CategorieRepository;
use App\Repository\TitreRepository;
use App\Repository\ArtisteRepository;
use App\Repository\AlbumRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SearchType;

use App\Repository\PlaylistRepository;
use Symfony\Component\Security\Core\Security;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

     /**
     * @var Security
     */
    private $security;

    
        protected $categoriess;
          protected $titres;
          protected $artistes;
           protected $albums;
           protected $user;
           protected $playlist;
          




	public function __construct(Security $security,
        CategorieRepository $categoriess,
        TitreRepository $titres,
        ArtisteRepository $artistes,
        AlbumRepository $albums,
        UserRepository $user,
        PlaylistRepository $playlist


    ){
        
            $this->categoriess=$categoriess;
         $this->titres=$titres;
         $this->user=$user;
         $this->playlist=$playlist;

         $this->security = $security;
         $this->artistes=$artistes;

         $this->albums=$albums;


    }

    


    /**
     * @Route("/", name="home")
     */
    public function index(Security $security,
        CategorieRepository $categoriess,
        AlbumRepository $albums,
        ArtisteRepository $artistes,
        UserRepository $user,
        PlaylistRepository $playlist,
        Request $request
        ): Response


    {	 $user = $this->security->getUser(); // null or UserInterface, if logged in

        $form = $this->createForm(SearchAnnonceType::class);
        $forme = $form->createView();
        $search = $form->handleRequest($request);
        $albumss = $albums->findBy([],['nom'=>'ASC'],3);
    	$categories = $categoriess->findBy([],['nom'=>'ASC'],5);
        $artistess = $artistes->findBy([],['nom'=>'ASC'],5);
        $countAllCategorie = $this->categoriess->countAllCategorie();
        $countAllAlbum = $this->albums->countAllAlbum();
        $countAllArtiste = $this->artistes->countAllArtiste();
        if($form->isSubmitted() && $form->isValid()){
            // On recherche les annonces correspondant aux mots clés
            $albums = $albumss->search(
                $search->get('mots')->getData(),
                $search->get('categorie')->getData(),

            );
        }

        if ($user){
        $play = $user->getPlaylists();
        return $this->render('home/index.html.twig', compact('albumss','categories','artistess','countAllCategorie','countAllAlbum','countAllArtiste','play','user','forme','albums'));

        }
        
            else{
        return $this->render('home/index.html.twig', compact('albumss','categories','artistess','countAllCategorie','countAllAlbum','countAllArtiste','user','forme','albums'));
    }
}
}
