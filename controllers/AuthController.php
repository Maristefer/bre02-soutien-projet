<?php

class AuthController extends AbstractController {

    private UserManager $um;
    
    public function __construct() {
        parent::__construct();
        $this->um = new UserManager();
    }
    
    public function register() : void
    {
        $this->render('front/register.html.twig', []);
    }
    
    /*$password = password_hash("test", PASSWORD_BCRYPT);
    $user = new User("test@test.fr", $password, "USER");
    $this->um->createUser($user);*/
    public function checkRegister() : void
    {
        //vérifie que tous les champs du formulaire (email, password, confirm_password) sont bien présents. 
        //Si ce n'est pas le cas elle redirige vers la page d'inscription et affiche un message d'erreur.
        if(isset($_POST["email"])
            && isset($_POST["password"]) && isset($_POST["confirm-password"]))
        {
            $tokenManager = new CSRFTokenManager();
            
            //Vérifie si le csrf_token est présent et utilise le CSRFTokenManager pour vérifier que le token reçu est le bon, 
            //si ça n'est pas le cas elle redirige vers la page d'inscription et affiche un message d'erreur.
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"]))
            {
                if($_POST["password"] === $_POST["confirm-password"])
                {
                    $password_pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\d\s])[A-Za-z\d^\w\s]{8,}$/';

                    if (preg_match($password_pattern, $_POST["password"]))
                    {
                        $um = new UserManager();
                        $user = $um->findUserByEmail($_POST["email"]);

                        if($user === null)
                        {
                            $email = htmlspecialchars($_POST["email"]);
                            $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
                            $role = htmlspecialchars($_POST["role"]);
                            $user = new User($email, $password, $role);

                            $um->createUser($user);

                            $_SESSION["user"] = $user->getId();

                            unset($_SESSION["error-message"]);

                            $this->redirect("index.php?route=connexion");
                        }
                        else
                        {
                            $_SESSION["error-message"] = "User already exists";
                            $this->redirect("index.php?route=inscription");
                        }
                    }
                    else {
                        $_SESSION["error-message"] = "Password is not strong enough";
                        $this->redirect("index.php?route=inscription");
                    }
                }
                else
                {
                    $_SESSION["error-message"] = "The passwords do not match";
                    $this->redirect("index.php?route=inscription");
                }
            }
            else
            {
                $_SESSION["error-message"] = "Invalid CSRF token";
                $this->redirect("index.php?route=inscription");
            }
        }
        else
        {
            $_SESSION["error-message"] = "Missing fields";
            $this->redirect("index.php?route=inscription");
        }
        
        
    }
    
    public function login() : void
    {
        $this->render('front/login.html.twig', []);
    }
    
     /*$user = $this->um->findUserByEmail("test@test.fr");
        dump($user);*/
    public function checkLogin() : void 
    {
       
         if(isset($_POST["email"]) && isset($_POST["password"]))
        {
            $tokenManager = new CSRFTokenManager();

            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"]))
            {
                $um = new UserManager();
                $user = $um->findUserByEmail($_POST["email"]);

                if($user !== null)
                {
                    if(password_verify($_POST["password"], $user->getPassword()))
                    {
                        $_SESSION["user"] = $user->getId();
                        $_SESSION["role"] = $user->getRole();

                        unset($_SESSION["error-message"]);

                        $this->redirect("index.php");
                    }
                    else
                    {
                        $_SESSION["error-message"] = "Invalid login information";
                        $this->redirect("index.php?route=connexion");
                    }
                }
                else
                {
                    $_SESSION["error-message"] = "Invalid login information";
                    $this->redirect("index.php?route=connexion");
                }
            }
            else
            {
                $_SESSION["error-message"] = "Invalid CSRF token";
                $this->redirect("index.php?route=connexion");
            }
        }
        else
        {
            $_SESSION["error-message"] = "Missing fields";
            $this->redirect("index.php?route=connexion");
        }
    }
    
    public function logout() : void
    {
        session_destroy();
        
        $this->redirect("index.php");
    }
}