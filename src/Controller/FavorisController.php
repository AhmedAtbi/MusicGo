<?php

namespace App\Controller;

use App\Entity\Favoris;
use App\Form\FavorisType;
use App\Repository\CategorieRepository;
use App\Repository\TitreRepository;
use App\Repository\ArtisteRepository;
use App\Repository\AlbumRepository;
use App\Repository\UserRepository;
use App\Repository\FavorisRepository;
use App\Repository\PlaylistRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/favoris")
 */
class FavorisController extends AbstractController
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
     * @Route("/", name="favoris_index", methods={"GET"})
     */
    public function index(TitreRepository $titres,Security $security,UserRepository $user,FavorisRepository $favorisRepository): Response
    {

        $user = $this->security->getUser();
        $play = $user->getPlaylists();
        $favori = $user->getTitres();
            
        return $this->render('favoris/index.html.twig', [
            'favori' => $favori,
            'user'=>$user,
            'play'=>$play,
       
            ]);}
    

    /**
     * @Route("/new", name="favoris_new", methods={"GET","POST"})
     */
    public function new(Security $security,UserRepository $user,Request $request): Response
    {

        $user = $this->security->getUser();
        $play = $user->getPlaylists();
        $favori = $user->getFav();
        $form = $this->createForm(FavorisType::class, $favori);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($favori);
            $entityManager->flush();

            return $this->redirectToRoute('favoris_index');
        }

        return $this->render('favoris/new.html.twig', [
            'favori' => $favori,
            'form' => $form->createView(),'user'=>$user,
            'play'=>$play,
        ]);
    }

    /**
     * @Route("/{id}", name="favoris_show", methods={"GET"})
     */
    public function show(Security $security,UserRepository $user): Response
    {

        $user = $this->security->getUser();
        $play = $user->getPlaylists();
        $favori = $user->getFav();
        return $this->render('favoris/show.html.twig', [
            'favori' => $favori,
            'user'=>$user,
            'play'=>$play,
        ]);
    }

    public function addFavorite(Security $security,UserRepository $user,EntityManagerInterface $manager, FavoriteRepository $favoriteRepository, Titre $titre)
    {

        $play = $user->getPlaylists();
        $favorite = $favoriteRepository->findOneBy([
            'titre' => $titre,
            'user' => $this->getUser()
        ]);

        if (!$favorite) {
            $favorite = new Favorite();
            $favorite
                ->setPost($titre)
                ->setUser($this->getUser());
            $manager->persist($favorite);
        } else {
            $manager->remove($favorite);
        }

        $manager->flush();

        return $this->render('favorite/index.html.twig');
    }

    /**
     * @Route("/{id}/edit", name="favoris_edit", methods={"GET","POST"})
     */
    public function edit(Security $security,UserRepository $user,Request $request, Favoris $favori): Response
    {

        $play = $user->getPlaylists();
        $form = $this->createForm(FavorisType::class, $favori);
        $form->handleRequest($request);
        $user = $this->security->getUser();
        $play = $user->getPlaylists();
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('favoris_index');
        }

        return $this->render('favoris/edit.html.twig', [
            'favori' => $favori,
            'form' => $form->createView(),'user'=>$user,
            'play'=>$play,
        ]);
    }

    /**
     * @Route("/{id}", name="favoris_delete", methods={"DELETE"})
     */
    public function delete(Security $security,UserRepository $user,Request $request, Favoris $favori): Response
    {

        $play = $user->getPlaylists();
        if ($this->isCsrfTokenValid('delete'.$favori->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($favori);
            $entityManager->flush();
        }

        return $this->redirectToRoute('favoris_index');
    }
}
