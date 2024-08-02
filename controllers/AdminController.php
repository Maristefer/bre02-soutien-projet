<?php

class AdminController extends AbstractController
{
    private UserManager $um;
    
    public function __construct(){
        parent::__construct();
        $this->um = new UserManager();
    }

    public function home() : void 
    {
        $this->render('admin/home.html.twig', []);
    }

    public function login() : void 
    {
        $this->render('admin/login.html.twig', []);
    }

    public function checkLogin() : void 
    {
        if(isset($_POST["email"]) && isset($_POST["password"]))
        {
            $tokenManager = new CSRFTokenManager();

            if(isset($_POST["csrf_token"]) && $tokenManager->validateCSRFToken($_POST["csrf_token"]))
            {
                $um = new UserManager();
                $user = $um->findUserByEmail($_POST["email"]);

                if($user !== null)
                {
                    if(password_verify($_POST["password"], $user->getPassword()) && $user->getRole() === "ADMIN")
                    {
                        $_SESSION["user"] = $user->getId();
                        $_SESSION["role"] = $user->getRole();

                        unset($_SESSION["error_message"]);

                        $this->redirect("admin");
                    }
                    else
                    {
                        $_SESSION["error_message"] = "Invalid login information";
                        $this->redirect("admin-connexion");
                    }
                }
                else
                {
                    $_SESSION["error_message"] = "Invalid login information";
                    $this->redirect("admin-connexion");
                }
            }
            else
            {
                $_SESSION["error_message"] = "Invalid CSRF token";
                $this->redirect("admin-connexion");
            }
        }
        else
        {
            $_SESSION["error_message"] = "Missing fields";
            $this->redirect("admin-connexion");
        }
        
    }
}