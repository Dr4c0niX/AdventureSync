<?php
require("models/Trip.php"); // on inclut de la classe Trip

class TripsManager
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

    public function create(Trip $newTrip): void //on crée un nouveau voyage
    {
        $usersManager = new UsersManager();
        $req = $this->pdo->prepare("INSERT INTO `trip` (title, description, address, country, startDate, endDate, collaborative, private, countOfPerson, image, userId) VALUES (:title, :description, :address, :country, :startDate, :endDate, :collaborative, :private, :countOfPerson, :image, :userId)");

        $req->bindValue(":title", htmlspecialchars($newTrip->getTitle()), PDO::PARAM_STR); //on utilise htmlspecialchars pour éviter les failles XSS
        $req->bindValue(":description", htmlspecialchars($newTrip->getDescription()), PDO::PARAM_STR);
        $req->bindValue(":address", htmlspecialchars($newTrip->getAddress()), PDO::PARAM_STR);
        $req->bindValue(":country", htmlspecialchars($newTrip->getCountry()), PDO::PARAM_STR);
        $req->bindValue(":startDate", htmlspecialchars($newTrip->getStartDate()), PDO::PARAM_STR);
        $req->bindValue(":endDate", htmlspecialchars($newTrip->getEndDate()), PDO::PARAM_STR);
        $req->bindValue(":collaborative", htmlspecialchars($newTrip->isCollaborative()), PDO::PARAM_BOOL);
        $req->bindValue(":private", htmlspecialchars($newTrip->isPrivate()), PDO::PARAM_BOOL);
        $req->bindValue(":countOfPerson", htmlspecialchars($newTrip->getCountOfPerson()), PDO::PARAM_INT);
        $req->bindValue(":image", htmlspecialchars($newTrip->getImage()), PDO::PARAM_STR);
        $req->bindValue(":userId", htmlspecialchars($usersManager->getByEmail($_SESSION["is_connected"])->getId()), PDO::PARAM_INT);
        $req->execute();
    }

    public function getById(int $id): Trip //on récupère un voyage par son id
    {
        $req = $this->pdo->prepare("SELECT * FROM trip WHERE id = :id");
        $req->bindValue(":id", $id, PDO::PARAM_INT);
        $req->execute();
        $data = $req->fetch();
        return new Trip($data);
    }

   public function getByUserEmail(string $email): Trip //on récupère un voyage par l'email de l'utilisateur
    {
        $req = $this->pdo->prepare("SELECT * FROM trip WHERE userId = :userId");
        $req->bindValue(":userId", $email, PDO::PARAM_STR);
        $req->execute();
        $data = $req->fetch();
        return new Trip($data);
    }

    public function getAllTripsByUserId($userId) { //on récupère tous les voyages d'un utilisateur
        $trips = array();
        $query = $this->pdo->prepare("SELECT * FROM trip WHERE userId = :userId");
        $query->execute(['userId' => $userId]);
    
        while ($data = $query->fetch(PDO::FETCH_ASSOC)) {
            $trips[] = new Trip($data);
        }
    
        return $trips;
    }

    public function getAll(): array //on récupère tous les voyages
    {
        $req = $this->pdo->query("SELECT * FROM `trip`");
        $req->execute();
        $data = $req->fetchAll();
        $trips = [];
        foreach ($data as $trip) {
            $trips[] = new Trip($trip);
        }
        return $trips;
    }

    public function update(Trip $trip): void //on met à jour un voyage
    {
        $req = $this->pdo->prepare("UPDATE trip SET title = :title, description = :description, address = :address, country = :country, startDate = :startDate, endDate = :endDate, collaborative = :collaborative, private = :private, countOfPerson = :countOfPerson, image = :image, userId = :userId WHERE id = :id");

        $req->bindValue(":title", htmlspecialchars($trip->getTitle()), PDO::PARAM_STR);
        $req->bindValue(":description", htmlspecialchars($trip->getDescription()), PDO::PARAM_STR);
        $req->bindValue(":address", htmlspecialchars($trip->getAddress()), PDO::PARAM_STR);
        $req->bindValue(":country", htmlspecialchars($trip->getCountry()), PDO::PARAM_STR);
        $req->bindValue(":startDate", htmlspecialchars($trip->getStartDate()), PDO::PARAM_STR);
        $req->bindValue(":endDate", htmlspecialchars($trip->getEndDate()), PDO::PARAM_STR);
        $req->bindValue(":collaborative", htmlspecialchars($trip->isCollaborative()), PDO::PARAM_BOOL);
        $req->bindValue(":private", htmlspecialchars($trip->isPrivate()), PDO::PARAM_BOOL);
        $req->bindValue(":countOfPerson", htmlspecialchars($trip->getCountOfPerson()), PDO::PARAM_INT);
        $req->bindValue(":image", htmlspecialchars($trip->getImage()), PDO::PARAM_STR);
        $req->bindValue(":userId", htmlspecialchars($trip->getUserId()), PDO::PARAM_INT);
        $req->bindValue(":id", htmlspecialchars($trip->getId()), PDO::PARAM_INT);
        $req->execute();
    }

    public function delete(int $id): void //on supprime un voyage
    {
        $req = $this->pdo->prepare("DELETE FROM trip WHERE id = :id");
        $req->bindValue(":id", $id, PDO::PARAM_INT);
        $req->execute();
    }
}
