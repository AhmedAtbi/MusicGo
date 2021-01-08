<?php

namespace App\Controller;

use App\Entity\Titre;
use App\Form\TitreType;
use App\Repository\TitreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\CategorieRepository;
use App\Repository\ArtisteRepository;
use App\Repository\AlbumRepository;
use App\Repository\UserRepository;
use App\Repository\PlaylistRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;





/**
 * @Route("/titre")
 */
class TitreController extends AbstractController
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
     * @Route("/", name="titre_index", methods={"GET"})
     */
    public function index(Security $security,UserRepository $user,TitreRepository $titreRepository): Response
    {
        $user = $this->security->getUser();
        $play = $user->getPlaylists();
         // null or UserInterface, if logged in
        return $this->render('titre/index.html.twig', [
            
            'titres' => $titreRepository->findAll(),'user'=>$user,
            'play'=>$play,
        ]);
    }

    /**
     * @Route("/new", name="titre_new", methods={"GET","POST"})
     */
    public function new(Security $security,Request $request,UserRepository $user): Response
    {


        
        $user = $this->security->getUser();
        $play = $user->getPlaylists();
        $titre = new Titre();
        $form = $this->createForm(TitreType::class, $titre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        
            $file = $form->get('url')-getData();
            $uploads_directory = $this->getParameter('uploads_directory');
            if ($file){
                $file = $form->get('url')-getData();
            $uploads_directory = $this->getParameter('uploads_directory');
            if ($file){
                $originalFilename = pathinfo ($file->getClientOriginal(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter($uploads_directory),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            
            }
            $titre->setUrl($newFilename);
            }
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($titre);
            $entityManager->flush();

            return $this->redirectToRoute('titre_index');
        }

        return $this->render('titre/new.html.twig', [
            'titre' => $titre,
            'form' => $form->createView(),
            'user'=>$user,
            'play'=>$play,
        ]);
    }

    /**
     * @Route("/favoris/ajout/{id}", name="ajout_favoris")
     */
      public function addFavorite(Titre $titre)
    {
        if (!$titre){
            throw new NotFoundHttpException('Pas de favoris trouvée');
        }
          $titre->addFav($this->getUser());
          $em = $this->getDoctrine()->getManager();
          $em->persist($titre);
          $em->flush();
          return $this->redirectToRoute('titre_index'); 
      }

      /**
     * @Route("/favoris/retrait/{id}", name="retrait_favoris")
     */
    public function retraitFavorite(Titre $titre)
    {
        if (!$titre){
            throw new NotFoundHttpException('Pas de favoris trouvée');
        }
          $titre->removeFav($this->getUser());
          $em = $this->getDoctrine()->getManager();
          $em->persist($titre);
          $em->flush();
          return $this->redirectToRoute('titre_index'); 

    }

    /**
     * @Route("/{id}", name="titre_show", methods={"GET"})
     */
    public function show(Security $security,Titre $titre): Response
    {   $user = $this->security->getUser();
        $play = $user->getPlaylists();
        return $this->render('titre/show.html.twig', [
            'titre' => $titre,'user'=>$user,
            'play'=>$play,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="titre_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Titre $titre): Response
    {   $user = $this->security->getUser();
        $play = $user->getPlaylists();
        $form = $this->createForm(TitreType::class, $titre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('titre_index');
        }

        return $this->render('titre/edit.html.twig', [
            'titre' => $titre,
            'form' => $form->createView(),'user'=>$user,
            'play'=>$play,
        ]);
    }

    /**
     * @Route("/{id}", name="titre_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Titre $titre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$titre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($titre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('titre_index');
    }
}
