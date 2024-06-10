<?php
require("models/Article.php"); // on inclut de la classe Article

class ArticlesManager
{
    private PDO $pdo;

    public function __construct() 
    {
        $dbName = "adventure_sync";
        $port = 3306;
        $userName = "root";
        $password = "MAMPrizea2024@";
        try {
            $this->setPdo(new PDO("mysql:host=localhost;dbname=$dbName;port=$port;charset=utf8mb4", $userName, $password));
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }

    public function setPdo(PDO $pdo): self
    {
        $this->pdo = $pdo;
        return $this;
    }

    public function create(Article $newArticle): void //on crée un nouvel article
    {
        $usersManager = new UsersManager();
        $req = $this->pdo->prepare("INSERT INTO `article` (title, description, address, country, startDate, endDate, image, userId) VALUES (:title, :description, :address, :country, :startDate, :endDate, :image, :userId)");

        $req->bindValue(":title", htmlspecialchars($newArticle->getTitle()), PDO::PARAM_STR); //on utilise htmlspecialchars pour éviter les failles XSS
        $req->bindValue(":description", htmlspecialchars($newArticle->getDescription()), PDO::PARAM_STR);
        $req->bindValue(":address", htmlspecialchars($newArticle->getAddress()), PDO::PARAM_STR);
        $req->bindValue(":country", htmlspecialchars($newArticle->getCountry()), PDO::PARAM_STR);
        $req->bindValue(":startDate", htmlspecialchars($newArticle->getStartDate()), PDO::PARAM_STR);
        $req->bindValue(":endDate", htmlspecialchars($newArticle->getEndDate()), PDO::PARAM_STR);
        $req->bindValue(":image", htmlspecialchars($newArticle->getImage()), PDO::PARAM_STR);
        $req->bindValue(":userId", htmlspecialchars($usersManager->getByEmail($_SESSION["is_connected"])->getId()), PDO::PARAM_INT);
        $req->execute();
    }

    public function getById(int $id): Article //on récupère un article par son id
    {
        $req = $this->pdo->prepare("SELECT * FROM article WHERE id = :id");
        $req->bindValue(":id", $id, PDO::PARAM_INT);
        $req->execute();
        $data = $req->fetch();
        return new Article($data);
    }

    public function getByUserEmail(string $email): Article //on récupère un article par l'email de l'utilisateur
    {
        $req = $this->pdo->prepare("SELECT * FROM article WHERE userId = :userId");
        $req->bindValue(":userId", $email, PDO::PARAM_STR);
        $req->execute();
        $data = $req->fetch();
        return new Article($data);
    }

    public function getAllArticlesByUserId($userId) { //on récupère tous les articles d'un utilisateur
        $articles = array();
        $query = $this->pdo->prepare("SELECT * FROM article WHERE userId = :userId");
        $query->execute(['userId' => $userId]);
    
        while ($data = $query->fetch(PDO::FETCH_ASSOC)) {
            $articles[] = new Article($data);
        }
    
        return $articles;
    }

    public function getAll(): array //on récupère tous les articles
    {
        $req = $this->pdo->prepare("SELECT * FROM article");
        $req->execute();
        $data = $req->fetchAll();
        $articles = [];
        foreach ($data as $article) {
            $articles[] = new Article($article);
        }
        return $articles;
    }

    public function update(Article $article): void //on met à jour un article
    {
        $req = $this->pdo->prepare("UPDATE article SET title = :title, description = :description, address = :address, country = :country, startDate = :startDate, endDate = :endDate, image = :image, userId = :userId WHERE id = :id");
        $req->bindValue(":title", htmlspecialchars($article->getTitle()), PDO::PARAM_STR);
        $req->bindValue(":description", htmlspecialchars($article->getDescription()), PDO::PARAM_STR);
        $req->bindValue(":address", htmlspecialchars($article->getAddress()), PDO::PARAM_STR);
        $req->bindValue(":country", htmlspecialchars($article->getCountry()), PDO::PARAM_STR);
        $req->bindValue(":startDate", htmlspecialchars($article->getStartDate()), PDO::PARAM_STR);
        $req->bindValue(":endDate", htmlspecialchars($article->getEndDate()), PDO::PARAM_STR);
        $req->bindValue(":image", htmlspecialchars($article->getImage()), PDO::PARAM_STR);
        $req->bindValue(":userId", htmlspecialchars($article->getUserId()), PDO::PARAM_INT);
        $req->bindValue(":id", $article->getId(), PDO::PARAM_INT);
        $req->execute();
    }

    public function delete(int $id): void //on supprime un article
    {
        $req = $this->pdo->prepare("DELETE FROM article WHERE id = :id");
        $req->bindValue(":id", $id, PDO::PARAM_INT);
        $req->execute();
    }
}