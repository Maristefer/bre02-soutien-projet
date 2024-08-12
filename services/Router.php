<?php

class Router 
{
    private DefaultController $dc;
    private AuthController $ac;
    private AdminController $adc;
    private UserController $uc;
    
    public function __construct()
    {
        $this->dc = new DefaultController();
        $this->ac = new AuthController();
        $this->adc = new AdminController();
        $this->uc = new UserController();
    }

    public function handleRequest(? string $route) : void 
    {
        if($route === null)
        {
            // le code si il n'y a pas de route ( === la page d'accueil)
            $this->dc->homePage();
        }
         else if($route === "home")
        {
             $this->dc->homePage();
        }
        else if($route === "inscription")
        {
            $this->ac->register();
        }
        else if($route === "check-inscription")
        {
            $this->ac->checkRegister();
        }
        else if($route === "connexion")
        {
            $this->ac->login();
        }
        else if($route === "check-connexion")
        {
            $this->ac->checkLogin();
        }
        else if($route === "deconnexion")
        {
            $this->ac->logout();
        }
        else if($route === "admin")
        {
            $this->checkAdminAccess();
            $this->adc->home();
        }    
        else if($route === "admin-connexion")
        {
            $this->adc->login();
        }
        else if($route === "admin-check-connexion")
        {
            $this->adc->checkLogin();
        }
        else if($route === "admin-deconnexion")
        {
            $this->adc->logout();
        }
        else if($route === "admin-create-user")
        {
            $this->checkAdminAccess();
            $this->uc->create();
        }
        else if($route === "admin-check-create-user")
        {
            $this->checkAdminAccess();
            $this->uc->checkCreate();
        }
        else if($route === "admin-edit-user")
        {
            $this->checkAdminAccess();
            
            if (isset($_GET['user_id'])) {
                $userId = (int)$_GET['user_id'];
                $this->uc->edit($userId);
            } else {
                $this->redirect("admin-list-user");
            }
        }
        else if($route === "admin-check-edit-user")
        {
            $this->checkAdminAccess();
            $this->uc->checkEdit();
        }
        else if($route === "admin-delete-user")
        {
            $this->checkAdminAccess();
            //vérifie si le paramètre $_GET['user_id'] existe.
            if(isset($_GET['user_id']))
            {
                //Si le paramètre existe, il est transformé en entier en utilisant le casting (int).
                $userId = (int)$_GET['user_id'];
                //Méthode show du UserController est appelée avec l'ID de l'utilisateur transformé en entier.
                $this->uc->delete($userId);
            }
            else
            {
                $this->redirect("admin-list-user");
            }
        }
        else if($route === "admin-list-user")
        {
            $this->checkAdminAccess();
            $this->uc->list();
        }
        else if($route === "admin-show-user")
        {
            $this->checkAdminAccess();
            //vérifie si le paramètre $_GET['user_id'] existe.
            if(isset($_GET['user_id']))
            {
                //Si le paramètre existe, il est transformé en entier en utilisant le casting (int).
                $userId = (int)$_GET['user_id'];
                //Méthode show du UserController est appelée avec l'ID de l'utilisateur transformé en entier.
                $this->uc->show($userId);
            }
            else
            {
                $this->redirect("admin-list-user");
            }
        }
        else
        {
            // le code si c'est aucun des cas précédents ( === page 404)
            $this->dc->notFound();
        }
    }
    
    private function checkAdminAccess(): void
    {
        if(isset($_SESSION['user']) 
            && isset($_SESSION['role']) && $_SESSION['role'] === "ADMIN")
            {
                // c'est bon
                //$this->adc->home();
            }
            else
            {
                     // c'est pas bon : redirection avec un header('Location:')
                     $this->redirect("admin-connexion");
            }
    }
    
    protected function redirect(? string $route) : void 
    {
        if($route !== null)
        {
            header("Location: index.php?route=$route");
        }
        else
        {
            header("Location: index.php");
        }
        exit();
    }   
}