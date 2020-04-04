<?php
// L ENTITY POUR LA PARTIE DU FILTRE
namespace App\Data;
use Symfony\Component\Validator\Constraints as Assert;

class SearchData
{

  
    // const TRI = [
    //     'Prix Croissant' => 0,
    //     'Prix Décroissant' => 1,
    //     'Nom Croissant' => 2,
    //     'Nom Décroissant' => 3
    // ];


    /**
     * @var string
     */
        public $q = '';


    /**
     * @var Category[]
     */
        public $categories = [];


     /**
     * @var Marques[]
     */
    public $marques = [];

    
    /**
     * @var null|integer
     * @Assert\GreaterThanOrEqual(propertyPath="min", message="Le prix maximum doit être inférieur ou égale au prix minimum" )
     */
        public $max;


    /**
     * @var null|integer
     * @Assert\LessThanOrEqual(propertyPath="max", message="Le prix minimum doit être inférieur ou égale au prix maximum" )
     */
        public $min;



    /**
     * @var string
     */
        public $ordre;


    /**
     * @var string
     */
        public $sexe;

 











}
