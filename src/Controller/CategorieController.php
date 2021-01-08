<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Repository\UserRepository;
use App\Repository\ArtisteRepository;
use App\Repository\AlbumRepository;


/**
 * @Route("/categorie")
 */
class CategorieController extends AbstractController
{
    protected $categorieRepo;

    /**
     * @var Security
     */
    private $security;
    protected $user;
    protected $artistes;
    protected $albums;
    

    public function __construct(Security $security, UserRepository $user,
        CategorieRepository $categorieRepo,
        ArtisteRepository $artistes,
        AlbumRepository $albums,
    ){
        $this->user=$user;
        $this->artistes=$artistes;

         $this->albums=$albums;
        $this->security = $security;
        $this->categorieRepo=$categorieRepo;

    }

    /**
     * @Route("/", name="categorie_index", methods={"GET"})
     */
    public function index(Security $security, 
    UserRepository $user, 
    CategorieRepository $categorieRepository,
    AlbumRepository $albums,
        ArtisteRepository $artistes
    ): Response
    
    {     
        
        $artistess= $artistes->findBy([],['nom'=>'ASC']);
        $user = $this->security->getUser();
        $play = $user->getPlaylists();
        $albumss = $albums->findBy([],['nom'=>'ASC']);
        $countAllCategorie=$this->categorieRepo->countAllCategorie();
        $user = $this->security->getUser();
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController', 
            'countAllCategorie'=>$this->categorieRepo->countAllCategorie(),
             'categories'=>$this->categorieRepo->findAll(),
             'user'=>$user,
             'countAllCategorie'=>$countAllCategorie,
             'albumss'=>$albumss,
             'artistess'=>$artistess,
             'play'=>$play,

                        ]);
    }

    /**
     * @Route("/new", name="categorie_new", methods={"GET","POST"})
     */
    public function new(Security $security,UserRepository $user,Request $request): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        $play = $user->getPlaylists();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
            'user'=>$user,
            'play'=>$play
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_show", methods={"GET"})
     */
    public function show(Security $security, UserRepository $user,Categorie $categorie): Response
    {       $user = $this->security->getUser();
        $play = $user->getPlaylists();
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
            'user'=>$user,
            'play'=>$play,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="categorie_edit", methods={"GET","POST"})
     */
    public function edit(Security $security,UserRepository $user,Request $request, Categorie $categorie): Response
    {   $play = $user->getPlaylists();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        $user = $this->security->getUser();
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),'user'=>$user,
            'play'=>$play,
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_delete", methods={"DELETE"})
     */
    public function delete(Security $security,UserRepository $user,Request $request, Categorie $categorie): Response
    {   $play = $user->getPlaylists();
        $user = $this->security->getUser();
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categorie_index');
    }
}
