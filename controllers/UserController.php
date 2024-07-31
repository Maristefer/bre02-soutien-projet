<?php

class UserController extends AbstractController
{
    private UserManager $um;
    
    public function __construct(){
        parent::__construct();
        $this->um = new UserManager();
    }

    public function create() : void {
        $this->render("admin/users/create.html.twig", []);
    }

    public function checkCreate() : void {

    }

    public function edit() : void {
        $this->render("admin/users/edit.html.twig", []);
    }

    public function checkEdit() : void {

    }

    public function delete() : void {

    }

    public function list() : void 
    {
        $users = $this->um->findAllUsers();
        $this->render("admin/users/list.html.twig", ['users' => $users]);
    }

    public function show() : void {
        $this->render("admin/users/show.html.twig", []);
    }
}