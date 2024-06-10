<?php
require("models/User.php"); // on inclut de la classe User

class UsersManager
{
    private PDO $db;

    public function __construct()
    {
        $dbName = "adventure_sync";
        $port = 3306;
        $userName = "root";
        $password = "MAMPrizea2024@";
        try {
            $this->setDb(new PDO("mysql:host=localhost;dbname=$dbName;port=$port;charset=utf8mb4", $userName, $password));
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }

    public function setDb(PDO $db): self
    {
        $this->db = $db;
        return $this;
    }

    public function create(User $newUser): void //on crée un nouvel utilisateur
    {
        $req = $this->db->prepare("INSERT INTO `user` (email, username, firstName, lastName, password, birthDate) VALUES (:email, :username, :firstName, :lastName, :password, :birthDate)");

        $req->bindValue(":email", htmlspecialchars($newUser->getEmail()), PDO::PARAM_STR);
        $req->bindValue(":username", htmlspecialchars($newUser->getUsername()), PDO::PARAM_STR);
        $req->bindValue(":firstName", htmlspecialchars($newUser->getFirstName()), PDO::PARAM_STR);
        $req->bindValue(":lastName", htmlspecialchars($newUser->getLastName()), PDO::PARAM_STR);
        $req->bindValue(":password", htmlspecialchars($newUser->getPassword()), PDO::PARAM_STR);
        $req->bindValue(":birthDate", htmlspecialchars($newUser->getBirthDate()), PDO::PARAM_STR);
        $req->execute();
    }

    public function getByEmail(string $email): User //on récupère un utilisateur par son email
    {
        $req = $this->db->prepare("SELECT * FROM user WHERE email = :email");
        $req->bindValue(":email", $email, PDO::PARAM_STR);
        $req->execute();
        $data = $req->fetch();
        return new User($data);
    }

    public function getUsersOrderedByEmail(): array //on récupère tous les utilisateurs par ordre alphabétique
    {
        $req = $this->db->query("SELECT * FROM `user` ORDER BY email");
        $req->execute();
        $data = $req->fetchAll();
        $users = [];
        foreach ($data as $user) {
            $users[] = new User($user);
        }
        return $users;
    }

    public function getById(int $id): User //on récupère un utilisateur par son id
    {
        $req = $this->db->prepare("SELECT * FROM user WHERE id = :id");
        $req->bindValue(":id", $id, PDO::PARAM_INT);
        $req->execute();
        $data = $req->fetch();
        return new User($data);
    }
    
    public function getLoggedInUser() { //on récupère l'utilisateur connecté
        if (isset($_SESSION["is_connected"])) {
            return $this->getByEmail($_SESSION["is_connected"]);
        }

        return null;
    }

    public function getAll(): array //on récupère tous les utilisateurs
    {
        $req = $this->db->query("SELECT * FROM `user`");
        $req->execute();
        $data = $req->fetchAll();
        $users = [];
        foreach ($data as $user) {
            $users[] = new User($user);
        }
        return $users;
    }

    public function emailExists($email)  //on vérifie si l'email existe
    {
        $query = $this->db->prepare("SELECT * FROM user WHERE email = :email");
        $query->execute(['email' => $email]);
        return $query->fetch() !== false;
    }

    public function update(User $user): void //on met à jour un utilisateur
    {
        $req = $this->db->prepare("UPDATE user SET email = :email, username = :username, firstName = :firstName, lastName = :lastName, password = :password, birthDate = :birthDate, admin = :admin WHERE id = :id");

        $req->bindValue(":email", htmlspecialchars($user->getEmail()), PDO::PARAM_STR);
        $req->bindValue(":username", htmlspecialchars($user->getUsername()), PDO::PARAM_STR);
        $req->bindValue(":firstName", htmlspecialchars($user->getFirstName()), PDO::PARAM_STR);
        $req->bindValue(":lastName", htmlspecialchars($user->getLastName()), PDO::PARAM_STR);
        $req->bindValue(":password", htmlspecialchars($user->getPassword()), PDO::PARAM_STR);
        $req->bindValue(":birthDate", htmlspecialchars($user->getBirthDate()), PDO::PARAM_STR);
        $req->bindValue(":id", htmlspecialchars($user->getId()), PDO::PARAM_INT);
        $req->bindValue(":admin", htmlspecialchars($user->isAdmin()), PDO::PARAM_BOOL);
        $req->execute();
    }

    public function delete(User $user): void //on supprime un utilisateur
    {
        $req = $this->db->prepare("DELETE FROM user WHERE id = :id");
        $req->bindValue(":id", $user->getId(), PDO::PARAM_INT);
        $req->execute();
    }
}