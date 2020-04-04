<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\UserRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/admin")
 */

class AdminController extends AbstractController
{
    /**
     * Cette fonction permet de lister le tableau de bord de l'admin
     * C'est également la redirection après la connexion pour un admin
     * 
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard()
    {   
        return $this->render('admin/dashboard.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    /**
     * Cette fonction permet de lister tous les produits
     * 
     * @Route("/liste_bijoux", name="admin_bijoux")
     */
    public function adminBijoux(ProduitRepository $repoProduit)
    {
        $produits = $repoProduit->findAll();
        return $this->render('admin/adminBijoux.html.twig', [
        'produits' => $produits,
        'user' => $this->getUser()
        ]);
    }

    /**
     * Cette fonction permet de créer un produit
     * 
     * @Route("/creation_bijoux", name="creationProduit")
     */
    public function creation( Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() )
        {
            // if(!$produit->getImageFile()){
            //     $produit->setImage('imageDefault.png');
            // }
            $produit->setUpdatedAt(new \DateTime('now'));
            $entityManagerInterface->persist($produit);
            $entityManagerInterface->flush();
            $this->addFlash("creationProduit", "La création a été effectuée");
            return $this->redirectToRoute("admin_bijoux");
        }


        return $this->render('admin/ajoutBijoux.html.twig', [
            "produit" => $produit,
            "form" => $form->createView(),
            'user' => $this->getUser()
        ]);
    }

    /**
     * Cette fonction permet de modifier un produit
     * 
     * @Route("/modification_bijoux/{id}", name="modificationProduit", methods="GET|POST")
     */
    public function modification(Produit $produit, Request $request, EntityManagerInterface $entityManagerInterface, CacheManager $cacheManager, UploaderHelper $helper)
    {
        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() )
        {
            if($produit->getImageFile() instanceof UploadedFile)
            {
                $cacheManager->remove($helper->asset($produit, "imageFile"));
            }

            $entityManagerInterface->persist($produit);
            $entityManagerInterface->flush();
            $this->addFlash("modificationProduit", "La modification a été effectuée");
            return $this->redirectToRoute("admin_bijoux");
        }


        return $this->render('admin/modificationBijoux.html.twig', [
            "produit" => $produit,
            "form" => $form->createView(),
            'user' => $this->getUser()
        ]);
    }


    /**
     * Cette fonction permet de supprimer un produit
     * 
     * @Route("/suppression_bijoux/{id}", name="suppressionProduit", methods="delete")
     */
    public function suppressionProduit(Produit $produit, Request $request, EntityManagerInterface $entityManagerInterface, CacheManager $cacheManager, UploaderHelper $helper)
    {
        if($this->isCsrfTokenValid("SUP". $produit->getId(), $request->get('_token') ))
        {
        $cacheManager->remove($helper->asset($produit, "imageFile"));    
        $entityManagerInterface->remove($produit);
        $entityManagerInterface->flush();
        $this->addFlash("suppressionProduit", "La suppression a été effectuée");
        return $this->redirectToRoute("admin_bijoux");
        }

    }





    /**
     * Cette fonction permet de lister de tous les membres 
     * 
     * @Route("/membres", name="membres")
     */
    public function membres(UserRepository $repoUser)
    {
        $membres = $repoUser->findAll();
        return $this->render('admin/membres.html.twig', [
        'user' => $this->getUser(),
        'membres' => $membres
        ]);
    }


    /**
     * Cette fonction permet de supprimer un membre
     * 
     * @Route("/user/suppressionUser/{id}", name="suppressionUser", methods="delete")
     */
    public function suppressionUser(User $user, Request $request, EntityManagerInterface $entityManagerInterface)
    {
        if($this->isCsrfTokenValid("SUP". $user->getId(), $request->get('_token') ))
        {
        $entityManagerInterface->remove($user);
        $entityManagerInterface->flush();
        $this->addFlash("suppressionUser", "La suppression a été effectuée");
        return $this->redirectToRoute("membres");
        }

    }
    
}

