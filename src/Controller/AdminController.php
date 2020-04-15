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


         // GRAPHIQUE
         $moisActuel = date("m"); // clé de départ, définir le mois actuel qui sera le mois de la dernière position du tableau
         $anneeActuelle = date("Y"); // clé de départ, définir l'année actuel qui sera l'année de la dernière position du tableau
         $tab24mois = []; // tableau regroupant les 12 mois de l'année actuelle + l'année passée, car les 12 mois se situeront sur cet interval
         $janvier = 01; // définir janvier comme 1er mois de l'année, passage de décembre -> janvier : 12->1
 
         //Création du tableau des 24 mois Y-1 + Y
         for ($y = date("Y") - 1; $y <= date("Y"); $y++)
         {
             for ($m = 1; $m <= 12; $m++)
             {
                 $n = strlen($m) == 1 ? strval("0" . $m) : strval($m);
                 $tab24mois[]  = $y . "-" . $n ;
             }
         }
 
         // définir mois+1, prenant en compte l'année
         if($moisActuel < 12)
         {
             if($moisActuel < 10 )
             {
                 $firstPosition = date( ($anneeActuelle-1) . '-0' . ($moisActuel +1));
             }
             else
             {
                 $firstPosition = date( ($anneeActuelle-1) . '-' . ($moisActuel +1));
             }
         }
         else
         {
             $firstPosition = date( $anneeActuelle . '-0' . $janvier );
         }
 
 
         // Position du premier mois du graphique dans les 24 mois du tableau
         $firstMonth = array_keys($tab24mois, $firstPosition);
 
 
         foreach ($firstMonth as $value)
         {
             $valueFirstMonth = $value;
         }
         //Création du tableau des 12 mois dont la derniere position est le mois actuel
         $tab12mois = array_slice($tab24mois, $valueFirstMonth, 12);

         $mois1 = count($repoUser->findAllBefore($tab12mois[0]));
         $mois2 = count($repoUser->findNumber($tab12mois[1]));
         $mois3 = count($repoUser->findNumber($tab12mois[2]));
         $mois4 = count($repoUser->findNumber($tab12mois[3]));
         $mois5 = count($repoUser->findNumber($tab12mois[4]));
         $mois6 = count($repoUser->findNumber($tab12mois[5]));
         $mois7 = count($repoUser->findNumber($tab12mois[6]));
         $mois8 = count($repoUser->findNumber($tab12mois[7]));
         $mois9 = count($repoUser->findNumber($tab12mois[8]));
         $mois10 = count($repoUser->findNumber($tab12mois[9]));
         $mois11 = count($repoUser->findNumber($tab12mois[10]));
         $mois12 = count($repoUser->findNumber($tab12mois[11]));

         // PARTIE NOM/CHIFFRE DU MOIS
         // Fournir la donnée du nom du mois en fonction de son numero pour le survol des graphiques
         // ex 1 = janvier, 2 = février etc...
         $boucleMois = [];
         for ($i = 0; $i < 12; $i++)
         {
         // on se focalise sur les 2 derniers caractères des valeurs de tab12mois soit "MM" de date
         $chiffreMois = intval(substr($tab12mois[$i], -2));
         //dump($nomPremierMois);
         
         $boucleMois[] = $chiffreMois;
 
         // on associe le chiffre à son nom de mois
         switch($chiffreMois)
         {
             case 1 : 
                $chiffreMois = "Janvier";
             break;
 
             case 2 : 
                 $chiffreMois = "Février";
             break;
 
             case 3 : 
                 $chiffreMois = "Mars";
             break;
 
             case 4 : 
                 $chiffreMois = "Avril";
             break;
 
             case 5 : 
                 $chiffreMois = "Mai";
             break;
 
             case 6 : 
                 $chiffreMois = "Juin";
             break;
 
             case 7 : 
                 $chiffreMois = "Juillet";
             break;
 
             case 8 : 
                 $chiffreMois = "Août";
             break;
 
             case 9 : 
                 $chiffreMois = "Septembre";
             break;
 
             case 10 : 
                 $chiffreMois = "Octobre";
             break;
 
             case 11 : 
                 $chiffreMois = "Novembre";
             break;
 
             case 12 : 
                 $chiffreMois = "Décembre";
             break;
 
             default;
         }
         
         $nomMois[] = $chiffreMois;
     }

 
         // le tableau User regroupe les 12 positions avec le nombre de Users mais également le numero du mois pour le label
         $tabGraph= [
 
            0 =>[
             $mois1,
             substr($tab12mois[0], -2),
             $nomMois[0]
            ],  
            1 =>[
             $mois2,
             substr($tab12mois[1], -2),
             $nomMois[1]
            ],  
            2 =>[
             $mois3,
             substr($tab12mois[2], -2),
             $nomMois[2]
            ],  
            3 =>[
             $mois4,
             substr($tab12mois[3], -2),
             $nomMois[3]
            ],  
            4 =>[
             $mois5,
             substr($tab12mois[4], -2),
             $nomMois[4]
            ],
            5 =>[
             $mois6,
             substr($tab12mois[5], -2),
             $nomMois[5]
            ],  
            6 =>[
             $mois7,
             substr($tab12mois[6], -2),
             $nomMois[6]
            ],  
            7 =>[
             $mois8,
             substr($tab12mois[7], -2),
             $nomMois[7]
            ],  
            8 =>[
             $mois9,
             substr($tab12mois[8], -2),
             $nomMois[8]
            ],  
            9 =>[
             $mois10,
             substr($tab12mois[9], -2),
             $nomMois[9]
            ],  
            10 =>[
             $mois11,
             substr($tab12mois[10], -2),
             $nomMois[10]
            ],  
            11 =>[
             $mois12,
             substr($tab12mois[11], -2),
             $nomMois[11]
            ]
 
         ];

        $membres = $repoUser->findAll();
        $nbMembres = count($repoUser->findAll());

        return $this->render('admin/membres.html.twig', [
        'user' => $this->getUser(),
        'membres' => $membres,
        'tabGraph' => $tabGraph,
        'nbMembres' => $nbMembres
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

