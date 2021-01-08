<?php

namespace App\Controller;

use App\Entity\Album;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;
use App\Repository\CategorieRepository;
use App\Repository\TitreRepository;
use App\Repository\ArtisteRepository;
use App\Repository\PlaylistRepository;

/**
 * @Route("/album")
 */
class AlbumController extends AbstractController
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
     * @Route("/", name="album_index", methods={"GET"})
     */
    public function index(UserRepository $user,AlbumRepository $albumRepository): Response
    {
        $user = $this->security->getUser(); 
        $play = $user->getPlaylists();
        return $this->render('album/index.html.twig', [
            'albums' => $albumRepository->findBy([],['nom'=>'ASC']),
            'play'=>$play,
            'user'=>$user,
        ]);
    }

    /**
     * @Route("/new", name="album_new", methods={"GET","POST"})
     */
    public function new(UserRepository $user,Request $request): Response
    {

        $play = $user->getPlaylists();
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($album);
            $entityManager->flush();

            return $this->redirectToRoute('album_index');
        }

        return $this->render('album/new.html.twig', [
            'album' => $album,
            'form' => $form->createView(),'play'=>$play,
        ]);
    }

    /**
     * @Route("/{id}", name="album_show", methods={"GET"})
     */
    public function show(UserRepository $user,Album $album): Response
    {

        $titres = $album->getTitres();
        $user = $this->security->getUser(); 
        $play = $user->getPlaylists();
        return $this->render('album/show.html.twig', [
            'album' => $album,'play'=>$play,'user'=>$user,'titres'=>$titres,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="album_edit", methods={"GET","POST"})
     */
    public function edit(UserRepository $user,Request $request, Album $album): Response
    {

        $play = $user->getPlaylists();
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('album_index');
        }

        return $this->render('album/edit.html.twig', [
            'album' => $album,
            'form' => $form->createView(),'play'=>$play,
        ]);
    }

    /**
     * @Route("/{id}", name="album_delete", methods={"DELETE"})
     */
    public function delete(UserRepository $user,Request $request, Album $album): Response
    {

        $play = $user->getPlaylists();
        if ($this->isCsrfTokenValid('delete'.$album->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($album);
            $entityManager->flush();
        }

        return $this->redirectToRoute('album_index');
    }
}
