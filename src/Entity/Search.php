<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

class Search {



private $minPrix;

private $maxPrix;

private $categorie;

private $genre;

private $matiere;




public function getMinPrix()
{
    return $this->minPrix;
}
public function setMinPrix($minPrix) 
{
    $this->minPrix = $minPrix;
    return $this;
}




public function getMaxPrix()
{
    return $this->maxPrix;
}
public function setMaxPrix($maxPrix)
{
    $this->maxPrix = $maxPrix;
    return $this;
}



public function getCategorie()
{
    return $this->categorie;
}
public function setCategorie($categorie)
{
    $this->categorie = $categorie;
    return $this;
}


public function getGenre()
{
    return $this->genre;
}
public function setGenre($genre)
{
    $this->genre = $genre;
    return $this;
}


public function getMatiere()
{
    return $this->matiere;
}
public function setMatiere($matiere)
{
    $this->matiere = $matiere;
    return $this;
}







}
