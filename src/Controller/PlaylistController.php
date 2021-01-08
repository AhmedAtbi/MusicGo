<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Entity\User;

use App\Form\PlaylistType;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Repository\UserRepository;



/**
 * @Route("/playlist")
 */
class PlaylistController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;
    protected $user;


    public function __construct(Security $security, UserRepository $user
    )
    
    {
        $this->user=$user;

        $this->security = $security;


    }

    /**
     * @Route("/", name="playlist_index", methods={"GET"})
     */
    public function index(UserRepository $user,Security $security,PlaylistRepository $playlistRepository): Response
    {$user = $this->security->getUser();
        $play=$user->getPlaylists();
        return $this->render('playlist/index.html.twig',compact('play','user'));
    }

    /**
     * @Route("/new", name="playlist_new", methods={"GET","POST"})
     */
    public function new(UserRepository $user,Security $security,Request $request): Response
    {   
        $playlist = new Playlist();
        
        $user = $this->security->getUser();
        $play = $user->getPlaylists();
        $playlist->setUtilisateur($user);
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);
        
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($playlist);
            $entityManager->flush();

            return $this->redirectToRoute('playlist_index');
        }

        return $this->render('playlist/new.html.twig', [
            'playlist' => $playlist,
            'form' => $form->createView(),'user'=>$user,'user'=>$user,
            'play'=>$play,
        ]);
    }

    /**
     * @Route("/{id}", name="playlist_show", methods={"GET"})
     */
    public function show(UserRepository $user,Security $security,Playlist $playlist): Response
    {       $user = $this->security->getUser();
        $play = $user->getPlaylists();
        $titres = $playlist->getTitre();
        return $this->render('playlist/show.html.twig', [
            'playlist' => $playlist,
            'user'=>$user,
            'play'=>$play,
            'titres'=>$titres,
        ]);
    }



    /**
     * @Route("/{id}/edit", name="playlist_edit", methods={"GET","POST"})
     */
    public function edit(UserRepository $user,Security $security,Request $request, Playlist $playlist): Response
    {       $user = $this->security->getUser();
        $play = $user->getPlaylists();
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('playlist_index');
        }

        return $this->render('playlist/edit.html.twig', [
            'playlist' => $playlist,
            'form' => $form->createView(),'user'=>$user,
            'play'=>$play,
        ]);
    }

    /**
     * @Route("/{id}", name="playlist_delete", methods={"DELETE"})
     */
    public function delete(Request $request,UserRepository $user,Security $security, Playlist $playlist): Response
    {           $user = $this->security->getUser();
        if ($this->isCsrfTokenValid('delete'.$playlist->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($playlist);
            $entityManager->flush();
        }

        return $this->redirectToRoute('playlist_index');
    }
}
