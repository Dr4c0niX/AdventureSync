<?php

class Trip 
{
    private int $id;
    private string $title;
    private string $description;
    private string $destination;
    private string $startDate;
    private string $endDate;
    private bool $collaborative;
    private bool $private; //true = public  & false = private
    private int $countOfPerson;
    private int $userId;

    public function __construct(array $data)
    {
        $this->hydrate($data);
        $this->collaborative = isset($data['collaborative']) ? (bool)$data['collaborative'] : false;
    }

    public function hydrate(array $data): void
    {
        foreach ($data as $key => $value) {
            $method = "set" . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }
    

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDestination(): string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): self
    {
        $this->destination = $destination;

        return $this;
    }

    public function getStartDate(): string
    {
        return $this->startDate;
    }

    public function setStartDate(string $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): string
    {
        return $this->endDate;
    }

    public function setEndDate(string $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function isCollaborative(): bool
    {
        return $this->collaborative;
    }

    public function setCollaborative(bool $collaborative): self
    {
        $this->collaborative = $collaborative;

        return $this;
    }

    public function isPrivate(): bool
    {
        return $this->private;
    }

    public function setPrivate(bool $private): self
    {
        $this->private = $private;

        return $this;
    }

    public function getCountOfPerson(): int
    {
        return $this->countOfPerson;
    }

    public function setCountOfPerson(int $countOfPerson): self
    {
        $this->countOfPerson = $countOfPerson;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
}