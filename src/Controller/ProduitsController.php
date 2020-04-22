<?php

namespace App\Controller;

use App\Entity\Search;
use App\Entity\Produit;
use App\Data\SearchData;
use App\Form\SearchForm;
use App\Form\SearchType;
use App\Repository\CategoriesRepository;
use App\Repository\MarquesRepository;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitsController extends AbstractController
{


    /**
     * @Route("/bijoux", name="bijoux")
     */
    public function bijoux(ProduitRepository $repoProduit,  Request $request)
    {
        $data = new SearchData();
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);
       

        $quantitePage = 12;// nombre de produits par page
        //$produits = $repoProduit->findAll();
        //$produitsNb = count($produits); // nombre de produits total
 
        // dump($produitsNb);
        // $nbPage = ceil($produitsNb/$quantitePage); // nombre de pages = nombre de produits total divisé par le nombre sur une page, arrondi à l'unité supérieure
        // dump($nbPage);

        

        // if($produits > 12)
        // {
        // if(isset($_GET['page']) AND !empty($_GET['page']) AND $_GET['page'] > 0 AND $_GET['page'] <= $nbPage)
        // // si $_GET['page'] existe, qu'elle est bien définie , superieure à 0 et inférieure ou égale au nombre de pages
        // {
        //     $_GET['page'] = intval($_GET['page']); // $_GET['page'] n'accepte que les chiffres
        //     $pageCourante = $_GET['page'];
             
        // }
        // else
        // {
        //     $pageCourante = 1; // sinon page = 1 
        // }
        // $start = ($pageCourante-1)*$quantitePage; // debut de chaque page, le premier chiffre de la limite
        // }
        $produits = $repoProduit->findSearch($data);
        //dump($produits);
        return $this->render('produits/produits.html.twig', [
        'produits' => $produits,
        'user' => $this->getUser(),
        'form' => $form->createView()
        // 'nbPage' => $nbPage,
        // 'pageCourante' => $pageCourante
        
        ]);
    }


    /**
     * Fonction qui est appelée dans le script du Twig Produits et qui renvoie un array pour l'autocomplémentation
     * 
     *  @param Request $request
     * @return JsonResponse $data
     * @Route("/ajaxAutocomplete", name="ajaxAutocomplete")
     */
    public function ajaxAutocomplete(ProduitRepository $repoProduit, MarquesRepository $repoMarque, CategoriesRepository $repoCategorie)
    {
        $nomsProduits = $repoProduit->findNom();
        $marquesProduits = $repoMarque->findMarques();
        $categoriesProduits = $repoCategorie->findCategories();

        $data = $this->jsonAutocomplete($nomsProduits, $marquesProduits, $categoriesProduits);
        return new JsonResponse($data);
     
    }

    
    /**
     * Fonction que permet de récupérer un array des noms marques et catégories de bijoux pour l'autocomplémentation
     * 
     * @param array $dpt
     * @return array $data
     */
    public function jsonAutocomplete ($nom, $marque, $categorie)
    {
        $data = array ();
        for ($i = 0; $i < count($nom); $i++)
        {
            $data[$i] = $nom[$i]['nom'] ;
        }

        for ($i = 0; $i < count($marque); $i++)
        {
            $data[$i] = $marque[$i]['libelle'] ;
        }

        for ($i = 0; $i < count($categorie); $i++)
        {
            $data[$i] = $categorie[$i]['libelle'] ;
        }

        return $data;

    }




    /**
     * @Route("/produit/{id}", name="produit")
     */
    public function produit(Produit $produit)
    {
        return $this->render('produits/produit.html.twig', [
        'produit' => $produit,
        'user' => $this->getUser()
        
        ]);
    }

}
