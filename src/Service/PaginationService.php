<?php
namespace App\Service;



use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;
use Symfony\Component\HttpFoundation\RequestStack;
/**
 * Class de pagination qui extrait tout notion de calcul et de recuperer des donnees de nos controller
 * 
 *  
 * @author dell
 *
 */
class PaginationService
{
    /**
     * nom de l'entityClass
     * @var object
     */
    private $entityClass;
    
    /**
     * limit d'enregistrement par page
     * 
     * @var integer
     */
    private $limit = 10;
    
    /**
     * la page actuelle
     * 
     * @var integer
     */
    private $currentPage;
    
    /**
     * manager doctrine pour recumerer les repos
     * 
     * @var object
     */
    private $manager;
    /**
     * pour recuperer la template twig
     * @var string
     */
    private $twig;
    
    /**
     * recuperer le nom de la route
     * 
     * @var string
     */
    private $route;
    
    /**
     * recuperer le chemin de la template twig
     * 
     * @var string
     */
    private $templatePath;
    
    public function __construct(EntityManagerInterface $manager, Environment $twig, RequestStack $request, 
        $templatePath){
        $this->route        = $request->getCurrentRequest()->attributes->get('_route');
        $this->manager      = $manager;
        $this->twig         = $twig;
        $this->templatePath = $templatePath;
    }
    
    public function display(){
        $this->twig->display($this->templatePath, [
            'page'  => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route
        ]);
    }
    
    public function getData(){
        if(empty($this->entityClass)){
            throw new \Exception("Vous n'avez pas specifier le nom de l'entityClass");
        }
        //1 calculer l'offset
        $offset = $this->currentPage * $this->limit - $this->limit;
        
        //2 demander au repo de trouver les éléments
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([], [], $this->limit, $offset);
        
        //3 renvoyer les éléments
        return $data;
    }
    
    public function getPages(){
        if(empty($this->entityClass)){
            throw new \Exception("Vous n'avez pas specifier le nom de l'entityClass");
        }
        //1 connaitre le total d'enregistrements de la table
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());
        
        //2  faire la devision, l'arrondi et le renvoyer
        $pages = ceil($total / $this->limit);
        return $pages;
    }
    
    public function getTemplatPath(){
        return $this->templatePath;
    }
    
    public function setTemplatpath($templatPath){
        $this->templatePath = $templatPath;
        return $this;
    }
    
    public function setRoute($route){
        $this->route = $route;
        return $this;
    }
    
    public function getRoute(){
        return $this->route;
    }
    
    public function setPage($page){
        $this->currentPage = $page;
        return $this;
    }
    
    public function getPage(){
        return $this->currentPage;
    }

    public function setLimit($limit){
        $this->limit = $limit;
        return $this;
    }
    
    public function getLimit(){
        return $this->limit;
    }
    
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }
}