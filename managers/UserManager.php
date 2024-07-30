<?php

class UserManager extends AbstractManager {

    public function __construct() {
        // J'appelle le constructeur de l'AbstractManager pour qu'il initialise la connexion à la DB
        parent::__construct();
    }
    
    //méthode qui permet d'entrer un nouvel utilisateur dans la base de données.
    //classe User en paramètre
    public function createUser(User $user) : User
    {
        $query = $this->db->prepare('INSERT INTO users (id, email, password, role) VALUES (NULL, :email, :password, :role)');
        $parameters = [
            "password" => $user->getPassword(),
            "email" => $user->getEmail(),
            "role" => $user->getRole(),
        ];

        $query->execute($parameters);
        
        $user->setId($this->db->lastInsertId());

        return $user;
    }
    
    //méthode qui permet de trouver un utilisateur dans la base de données à partir de son email.
    //Attention à bien respecter le prototype de la méthode, elle retourne soit null, soit un User et prend une string en paramètres
    public function findUserByEmail(string $email) : ? User
    {
        $query = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $parameters = [
            "email" => $email,
        ];
        $query->execute($parameters);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if($user !== null)
        {
            $item = new User($user["email"], $user["password"], $user["role"]);
            $item->setId($user["id"]);
            
            return $item;
        }
        else
        {
            return null;
        }
    }
    
}