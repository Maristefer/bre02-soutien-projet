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
    
    public function checkRegister() : void
    {
        $password = password_hash("test", PASSWORD_BCRYPT);
        $user = new User("test@test.fr", $password, "USER");
    
        $this->um->createUser($user);
    }
    
    public function login() : void
    {
        $this->render('front/login.html.twig', []);
    }
    
    public function checkLogin() : void 
    {
        $user = $this->um->findUserByEmail("test@test.fr");
        dump($user);
    }
    
    public function logout() : void
    {
        
    }
}