<?php

class AdminController extends AbstractController
{
    public function __construct(){
        parent::__construct();
    }

    public function home() : void 
    {
        $this->render('admin/home.html.twig', []);
    }

    public function login() : void 
    {
        $this->render('admin/login.html.twig', []);
    }

    public function checkLogin() : void {
        
    }
}