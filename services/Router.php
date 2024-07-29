<?php

class Router {
    private DefaultController $dc;
    
    public function __construct()
    {
        $this->dc = new DefaultController();
    }

    public function handleRequest(? string $route) : void {
        if($route === null)
        {
            // le code si il n'y a pas de route ( === la page d'accueil)
            $this->dc->homePage();
        }
        else
        {
            // le code si c'est aucun des cas précédents ( === page 404)
             $this->dc->notFound();
        }
    }
}