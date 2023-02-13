<?php
namespace Core\Database;
class DatabaseQuery
{
    protected $PDO;
    protected $query;
    protected $table;
    protected $parameters = [];
    public static function new(): DatabaseQuery
    {
        $db = new DatabaseQuery();
        $db->connect("mvc_school", "root", "root");
        return $db;
    }

    public function setQuery($query): static
    {
        $this->query = $query;
        return $this;
    }

    public function setParameters($parameters): static
    {
        $this->parameters = $parameters;
        return $this;
    }

    public function connect($database_name, $username, $password, $host = 'localhost') : static
    {
        $this->PDO = new \PDO("mysql:dbname={$database_name};host={$host}", $username, $password);
        return $this;
    }

    /**
     * @throws \Exception
     */
    public function execute(){
        $stmt = $this->PDO->prepare($this->query);
        // dump complete query with parameters
        try {
            $stmt->execute($this->parameters);
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
        return $stmt->fetchAll(\PDO::FETCH_CLASS);
        //return $this->PDO->execute($this->parameters);
    }

    public function lastInsertId($table){
        $stmt = $this->PDO->query("SELECT LAST_INSERT_ID() from {$table}");
        return $stmt->fetchColumn();
    }
}