<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Entity\Artiste;
use App\Entity\Album;
use App\Entity\Titre;
use App\Entity\User;
use App\Repository\CategorieRepository;
use App\Repository\UserRepository;
use App\Repository\ArtisteRepository;



use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    protected $userRepo;
    protected $categorieRepo;
    protected $artistRepo;

    public function __construct(
        UserRepository $userRepo,
        CategorieRepository $categorieRepo,
        ArtisteRepository $artistRepo

    ){
        $this->userRepo=$userRepo;
        $this->categorieRepo=$categorieRepo;
        $this->artistRepo=$artistRepo;

    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('bundles/EasyAdminBundle/welcome.html.twig',[
        'countAllUsers'=>$this->userRepo->countAllUsers(),
        'countAllCategorie'=>$this->categorieRepo->countAllCategorie(),
        'categories'=>$this->categorieRepo->findAll(),
        'artistes'=>$this->artistRepo->findAll(),
        'countAllArtiste'=>$this->artistRepo->countAllArtiste()

        ]);

    }
             
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Music Goo');
    }

    
        public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Categorie', 'fa fa-podcast', Categorie::class);
        yield MenuItem::linkToCrud('Artiste', 'fa fa-star', Artiste::class);
        yield MenuItem::linkToCrud('Album', 'fa fa-folder', Album::class);
        yield MenuItem::linkToCrud('Titre', 'fa fa-play', Titre::class);
        yield MenuItem::linkToCrud('Utilisateur', 'fa fa-address-book-o', User::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
    }
