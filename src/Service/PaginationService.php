<?php

namespace App\Service;

use Twig\Environment;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginationService {

    private $entityClass;
    private $limit = 10;
    private $currentPage;
    private $manager;
    private $route;
    private $twig;

    public function __construct(ObjectManager $manager, Environment $twig,RequestStack $request){
          $this->manager = $manager;
          $this->twig    = $twig;
          $this->route = $request->getCurrentRequest()->attributes->get('_route');
    }

    public function display(){
        $this->twig->display('/admin/partials/pagination.html.twig',[
              'page'  => $this->getPage(),
              'pages' => $this->getPages(),
              'route_name' =>  $this->getRoute()
        ]);
    }

    public function setEntityClass($entity){
         $this->entityClass = $entity;
         return $this;
    }
    public function getEntityClass(){
        return $this->entityClass;
    }
    public function setRoute($route){
         $this->route = $route;
         return $this;
    }
    public function getRoute(){
        return $this->route;
    }
    public function setLimit($nbr){
        $this->limit = $nbr;
        return $this;
    }
    public function getLimit(){
        return $this->limit;
    }
    public function setPage($page){
        $this->currentPage = $page;
        return $this;
    }
    public function getPage(){
        return $this->currentPage;
    }

    public function getPages(){
        if(empty($this->entityClass)){
            throw new \Exception("Vous devez passer les parametre au votre objet pagination");
        }
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());
        return ceil($total / $this->getLimit() );
    }

    public function getData(){
        if(empty($this->entityClass)){
            throw new \Exception("Vous devez passer les parametre au votre objet pagination");
        }
        $repo = $this->manager->getRepository($this->entityClass);
        $offset =  $this->getPage() * $this->getLimit() - $this->getLimit();
        return $repo->findBy([],[],$this->getLimit(),$offset);
     }
}