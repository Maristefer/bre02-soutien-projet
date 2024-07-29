<?php

class Router {

    public function handleRequest(? string $route) : void {
        if($route === null)
        {
            // le code si il n'y a pas de route ( === la page d'accueil)
            echo "je dois afficher la page d'accueil";
        }
        else
        {
            // le code si c'est aucun des cas précédents ( === page 404)
            echo "je dois afficher la page 404";
        }
    }
}