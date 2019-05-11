<?php

declare(strict_types=1);

namespace Core;

class BaseSQL
{
    private $pdo;
    private $table;

    public function __construct(
        string $driver,
        string $host,
        string $name,
        string $user,
        string $password
    ) {
        try {
            // $this->pdo = new PDO(DBDRIVER . ":host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPWD);
            $this->pdo = new \PDO($driver.":host=".$host.";dbname=".$name, $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $exception) {
            // die("Erreur SQL : " . $exception->getMessage());
            throw new \Exception("SQL Error: ".$exception->getMessage());
        }

        $this->table = get_called_class();
    }


    public function setId(int $id)
    {
        $this->id = $id;
        $this->getOneBy(["id" => $id], true);
    }



    public function getOneByArray(array $where): array
    {
        $sqlWhere = $this->extractWhereClauses($where);

        $sql = " SELECT * FROM " . $this->table . " WHERE  " . implode(" AND ", $sqlWhere) . ";";
        $query = $this->pdo->prepare($sql);

        $query->setFetchMode(PDO::FETCH_ASSOC);

        $query->execute($where);
        return $query->fetch();
    }


    public function getOneByObject(array $where): Object
    {
        $sqlWhere = $this->extractWhereClauses($where);

        $sql = " SELECT * FROM " . $this->table . " WHERE  " . implode(" AND ", $sqlWhere) . ";";
        $query = $this->pdo->prepare($sql);

        $query->setFetchMode(PDO::FETCH_INTO, $this); // warning : attention à $this

        $query->execute($where);
        return $query->fetch();
    }

    // change my name, please ;D
    // take an array with key/value pair
    // return an array with "key:=value" value
    // Example :
    // INPUT  : User[firstname: hassan, lastname: ariz]
    // OUTPUT : User["firstname=:hassan", "lastname=:ariz"]
    private function extractWhereClauses(array $where):array
    {
        $sqlWhere = [];
        foreach ($where as $key => $value) {
            $sqlWhere[] = $key . "=:" . $key;
        }
        return $sqlWhere;
    }

    public function save(): void
    {
        // cast user instance (object) to array
        $dataObject = get_object_vars($this);

        // cast user class to array
        // and keep non default properties
        $dataChild = array_diff_key($dataObject, get_class_vars(get_class()));

        // check if the user has an id
        if (is_null($dataChild["id"])) {
            $this->createUser($dataChild);
        } else {
            $this->updateUser($dataChild);
        }
    }

    private function createUser(Array $dataChild): void
    {
        $sql = "INSERT INTO " . $this->table . " ( " .
            implode(",", array_keys($dataChild)) . ") VALUES ( :" .
            implode(",:", array_keys($dataChild)) . ")";

        $query = $this->pdo->prepare($sql);
        $query->execute($dataChild);
    }

    // SELECT LASTNAME + FIRSTNAME AS FULLNAME
    // FROM USER
    // WHERE ID =:ID AND NAME:=NAME AND LAST=:LAST

    private function updateUser(Array $dataChild): void
    {
        $sqlUpdate = extractWhereClauses($dataChild);
        // UPDATE USER SET lastname=:ariz, firstname=:hassan WHERE id=:id
        // prepare
        // execute

        // UPDATE USER SET lastname=:lastname, firstname=:firstname WHERE id=:id
        // prepare
        // set argument id = 5
        // set argument firstname = hassan
        // set argument lastname = ariz
        // execute


        $sql = "UPDATE " . $this->table . " SET " . implode(",", $sqlUpdate) . " WHERE id=:id";

        $query = $this->pdo->prepare($sql);
        $query->execute($dataChild);
    }


}

