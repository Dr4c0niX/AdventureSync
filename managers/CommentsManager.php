<?php
require("models/Comment.php");

class CommentsManager
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

    public function create(Comment $newComment): void
    {
        $usersManager = new UsersManager();
        $articlesManager = new ArticlesManager();
        $tripsManager = new TripsManager();
        $req = $this->pdo->prepare("INSERT INTO `comment` (rating, comment, date, userId, articleId, tripId) VALUES (:rating, :comment, :date, :userId, :articleId, :tripId)");
    
        $req->bindValue(":rating", htmlspecialchars($newComment->getRating()), PDO::PARAM_INT);
        $req->bindValue(":comment", htmlspecialchars($newComment->getComment()), PDO::PARAM_STR);
        $req->bindValue(":date", htmlspecialchars($newComment->getDate()), PDO::PARAM_STR);
        $req->bindValue(":userId", htmlspecialchars($usersManager->getByEmail($_SESSION["is_connected"])->getId()), PDO::PARAM_INT);
        $req->bindValue(":articleId", htmlspecialchars($articlesManager->getById($newComment->getArticleId())->getId()), PDO::PARAM_INT);
        $req->bindValue(":tripId", htmlspecialchars($tripsManager->getById($newComment->getTripId())->getId()), PDO::PARAM_INT);
        $req->execute();
    }

    public function getById(int $id): Comment
    {
        $req = $this->pdo->prepare("SELECT * FROM comment WHERE id = :id");
        $req->bindValue(":id", $id, PDO::PARAM_INT);
        $req->execute();
        $data = $req->fetch();
        return new Comment($data);
    }

    public function getByUserEmail(string $email): Comment
    {
        $req = $this->pdo->prepare("SELECT * FROM comment WHERE userId = :userId");
        $req->bindValue(":userId", $email, PDO::PARAM_STR);
        $req->execute();
        $data = $req->fetch();
        return new Comment($data);
    }

    public function getByArticleId(int $articleId): array
    {
        $req = $this->pdo->prepare("SELECT * FROM comment WHERE articleId = :articleId");
        $req->bindValue(":articleId", $articleId, PDO::PARAM_INT);
        $req->execute();
        $data = $req->fetchAll();
        $comments = [];
        foreach ($data as $comment) {
            $comments[] = new Comment($comment);
        }
        return $comments;
    }

    public function getByTripId(int $tripId): array
    {
        $req = $this->pdo->prepare("SELECT * FROM comment WHERE tripId = :tripId");
        $req->bindValue(":tripId", $tripId, PDO::PARAM_INT);
        $req->execute();
        $data = $req->fetchAll();
        $comments = [];
        foreach ($data as $comment) {
            $comments[] = new Comment($comment);
        }
        return $comments;
    }

    public function getAll(): array
    {
        $req = $this->pdo->prepare("SELECT * FROM comment");
        $req->execute();
        $data = $req->fetchAll();
        $comments = [];
        foreach ($data as $comment) {
            $comments[] = new Comment($comment);
        }
        return $comments;
    }

    public function update(Comment $comment): void
    {
        $req = $this->pdo->prepare("UPDATE comment SET rating = :rating, comment = :comment, date = :date, userId = :userId, articleId = :articleId, tripId = :tripId WHERE id = :id");
        $req->bindValue(":rating", htmlspecialchars($comment->getRating()), PDO::PARAM_INT);
        $req->bindValue(":comment", htmlspecialchars($comment->getComment()), PDO::PARAM_STR);
        $req->bindValue(":date", htmlspecialchars($comment->getDate()), PDO::PARAM_STR);
        $req->bindValue(":userId", htmlspecialchars($comment->getUserId()), PDO::PARAM_INT);
        $req->bindValue(":articleId", htmlspecialchars($comment->getArticleId()), PDO::PARAM_INT);
        $req->bindValue(":tripId", htmlspecialchars($comment->getTripId()), PDO::PARAM_INT);
        $req->bindValue(":id", htmlspecialchars($comment->getId()), PDO::PARAM_INT);
        $req->execute();
    }

    public function delete(int $id): void
    {
        $req = $this->pdo->prepare("DELETE FROM comment WHERE id = :id");
        $req->bindValue(":id", $id, PDO::PARAM_INT);
        $req->execute();
    }
}    