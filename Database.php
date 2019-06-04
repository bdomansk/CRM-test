<?php
class Database {
    protected $pdo;
    public  $commandsAmount = 0;
    public  $requests = [];

    public function __construct(){
        $database = require "config/databaseConfigTask2.php";
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ];
        $this->pdo = new \PDO($database['dsn'], $database['user'], $database['pass'], $options);
        $this->commandsAmount++;
        $this->requests[] =  "CREATE DATABASE IF NOT EXISTS " . $database['name'];
        $this->pdo->exec("CREATE DATABASE IF NOT EXISTS " . $database['name']);
        $dsn = $database['dsn'] . ';dbname=' . $database['name'];
        $this->pdo = new \PDO($dsn, $database['user'], $database['pass']);
        $tables = require "config/databaseStructureTask2.php";
        foreach ($tables as $createCommand) {
            $this->commandsAmount++;
            $this->requests[] =$createCommand;
            $this->pdo->exec($createCommand);
        }
    }

    public function execute($sqlRequest, $parameters = []){
        $this->requests[] = $sqlRequest;
        $this->commandsAmount++;
        $statement = $this->pdo->prepare($sqlRequest);
        return $statement->execute($parameters);
    }

    public function request($sqlRequest, $parameters = []){
        $this->requests[] = $sqlRequest;
        $this->commandsAmount++;
        $statement = $this->pdo->prepare($sqlRequest);
        $result = $statement->execute($parameters);
        if ($result !== false){
            return $statement->fetchAll();
        }
        return [];
    }

    public function viewCommands(){
        $i = 0;
        echo "Total was use $this->commandsAmount comands:<br>";
        foreach ($this->requests as $command){
            echo "$i) $command <br><br>";
            $i++;
        }
    }
}
?>
