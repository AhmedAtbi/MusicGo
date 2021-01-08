<?php

namespace App\Controller;

use App\Entity\Artiste;
use App\Form\ArtisteType;
use App\Repository\ArtisteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;
use App\Repository\CategorieRepository;
use App\Repository\TitreRepository;
use App\Repository\AlbumRepository;
use App\Repository\PlaylistRepository;


/**
 * @Route("/artiste")
 */
class ArtisteController extends AbstractController
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
     * @Route("/", name="artiste_index", methods={"GET"})
     */
    public function index(Security $security,UserRepository $user,ArtisteRepository $artisteRepository): Response
    {
        $user = $this->security->getUser(); 
        $play = $user->getPlaylists();
        return $this->render('artiste/index.html.twig', [
            'artistes' => $artisteRepository->findAll(),'play'=>$play,'user'=>$user,
        ]);
    }

    /**
     * @Route("/new", name="artiste_new", methods={"GET","POST"})
     */
    public function new(UserRepository $user,Request $request): Response
    {

        $play = $user->getPlaylists();
        $artiste = new Artiste();
        $form = $this->createForm(ArtisteType::class, $artiste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($artiste);
            $entityManager->flush();

            return $this->redirectToRoute('artiste_index');
        }

        return $this->render('artiste/new.html.twig', [
            'artiste' => $artiste,
            'form' => $form->createView(),'play'=>$play,
        ]);
    }

    /**
     * @Route("/{id}", name="artiste_show", methods={"GET"})
     */
    public function show(UserRepository $user,Artiste $artiste): Response
    {

        $albums = $artiste->getAlbums();
        
        $user = $this->security->getUser(); 
        $play = $user->getPlaylists();
        return $this->render('artiste/show.html.twig', [
            'artiste' => $artiste,
            'user'=>$user,
            'play'=>$play,
            'albums'=>$albums,
            

        ]);
    }

    /**
     * @Route("/{id}/edit", name="artiste_edit", methods={"GET","POST"})
     */
    public function edit(UserRepository $user,Request $request, Artiste $artiste): Response
    {
        $user = $this->security->getUser();
        $play = $user->getPlaylists();
        $form = $this->createForm(ArtisteType::class, $artiste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('artiste_index');
        }

        return $this->render('artiste/edit.html.twig', [
            'artiste' => $artiste,
            'form' => $form->createView(),
            'play'=>$play,
        ]);
    }

    /**
     * @Route("/{id}", name="artiste_delete", methods={"DELETE"})
     */
    public function delete(UserRepository $user,Request $request, Artiste $artiste): Response
    {

        $play = $user->getPlaylists();
        if ($this->isCsrfTokenValid('delete'.$artiste->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($artiste);
            $entityManager->flush();
        }

        return $this->redirectToRoute('artiste_index');
    }
}
