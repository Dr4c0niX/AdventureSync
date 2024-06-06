<?php
require("models/Article.php");

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

    public function create(Article $newArticle): void
    {
        $usersManager = new UsersManager();
        $req = $this->pdo->prepare("INSERT INTO `article` (title, description, destination, startDate, endDate, userId) VALUES (:title, :description, :destination, :startDate, :endDate, :userId)");

        $req->bindValue(":title", htmlspecialchars($newArticle->getTitle()), PDO::PARAM_STR);
        $req->bindValue(":description", htmlspecialchars($newArticle->getDescription()), PDO::PARAM_STR);
        $req->bindValue(":destination", htmlspecialchars($newArticle->getDestination()), PDO::PARAM_STR);
        $req->bindValue(":startDate", htmlspecialchars($newArticle->getStartDate()), PDO::PARAM_STR);
        $req->bindValue(":endDate", htmlspecialchars($newArticle->getEndDate()), PDO::PARAM_STR);
        $req->bindValue(":userId", htmlspecialchars($usersManager->getByEmail($_SESSION["is_connected"])->getId()), PDO::PARAM_INT);
        $req->execute();
    }

    public function getById(int $id): Article
    {
        $req = $this->pdo->prepare("SELECT * FROM article WHERE id = :id");
        $req->bindValue(":id", $id, PDO::PARAM_INT);
        $req->execute();
        $data = $req->fetch();
        return new Article($data);
    }

    public function getByUserEmail(string $email): Article
    {
        $req = $this->pdo->prepare("SELECT * FROM article WHERE userId = :userId");
        $req->bindValue(":userId", $email, PDO::PARAM_STR);
        $req->execute();
        $data = $req->fetch();
        return new Article($data);
    }

    public function getAll(): array
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

    public function update(Article $article): void
    {
        $req = $this->pdo->prepare("UPDATE article SET title = :title, description = :description, destination = :destination, startDate = :startDate, endDate = :endDate, userId = :userId WHERE id = :id");
        $req->bindValue(":title", htmlspecialchars($article->getTitle()), PDO::PARAM_STR);
        $req->bindValue(":description", htmlspecialchars($article->getDescription()), PDO::PARAM_STR);
        $req->bindValue(":destination", htmlspecialchars($article->getDestination()), PDO::PARAM_STR);
        $req->bindValue(":startDate", htmlspecialchars($article->getStartDate()), PDO::PARAM_STR);
        $req->bindValue(":endDate", htmlspecialchars($article->getEndDate()), PDO::PARAM_STR);
        $req->bindValue(":userId", htmlspecialchars($article->getUserId()), PDO::PARAM_INT);
        $req->bindValue(":id", $article->getId(), PDO::PARAM_INT);
        $req->execute();
    }

    public function delete(int $id): void
    {
        $req = $this->pdo->prepare("DELETE FROM article WHERE id = :id");
        $req->bindValue(":id", $id, PDO::PARAM_INT);
        $req->execute();
    }
}