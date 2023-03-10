<?php
namespace Core\Database;
use Core\Database\Filters;
use Core\Database\Relations\HasMany;
use Exception;
use ReflectionClass;
use ReflectionException;
use stdClass;
/*enum QueryType
{
    case SINGLE;
    case MULTIPLE;
}*/

$QueryType = [
    "SINGLE" => 1,
    "MULTIPLE" => 2
];

class Elegant
{
    use Filters;

    public $relations = [];

    /**
     * The id of the model instance, used for updating and deleting
     */
    public $id = null;

    /**
     * The name of the table this model is associated with
     */
    protected $table;
    protected $fields = [];
    /**
     * The primary key of the table
     */
    protected $primaryKey = 'id';

    /**
     * Used for storing the database query
     */
    protected $query = "";

    /**
     * Used for storing parameters for the database query
     */
    protected $parameters = [];

    public function __construct()
    {
        $this->table = strtolower($this->get_class_name($this));
        $this->table .= 's';

    }

    /**
     * @throws ReflectionException
     */
    private function get_class_name($class): string
    {
        $reflectionObject = new ReflectionClass($class);
        return $reflectionObject->getShortName();
    }

    public static function all()
    {
        $db = DatabaseQuery::new();
        $model = new static();
        $db->setQuery("SELECT * FROM {$model->table}");
        return $db->execute();

    }
    /**
     * Include a relation in the database query
     * Relation must be defined in a function with the same name
     * In the model.
     * @throws Exception
     */
    public static function with(string $relation): static
    {
        $model = new static();
        $model->relations[] = $relation;
        return $model;
    }

    public function load(string $relation) : void
    {
        $this->relations[] = $relation;
        $classValues = [

        ];
        $this->get_relations($this);
    }

    /**
     * Searches for a specific item in the table
     * @param $id
     * @throws Exception
     */
    public static function search($id): stdClass
    {
        $eq = new static();
        return $eq->find($id);
    }

    /**
     *
     * Retrieves a single record from the database
     * based on the id or primary key
     * Must be numeric
     * @param $id
     * @return stdClass
     * @throws Exception
     */
    public function find($id): stdClass
    {
        $baseQuery = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $db = DatabaseQuery::new();
        $db->setQuery($baseQuery);
        $db->setParameters(['id' => $id]);

        $result = $db->execute();
        if (empty($result)) {
            throw new Exception("No record found with id {$id}");
        }
        $result = $result[0];

        if (empty($result)) {
            throw new Exception("No record found with id {$id}");
        }

        $this->get_relations($result);
        return $result;
    }

    private function get_relations(&$result): void
    {
        $type = 1;
        if (!$result instanceof stdClass) {
            $type = 2;
        }

        foreach ($this->relations as $relation) {
            $relation = $this->$relation(false);
            $relation->execute_relation($result, $type, $this->primaryKey);
        }
    }

    /**
     * Creates a new instance of the model
     * @return static
     */
    public static function retrieve(): static
    {
        return new static();
    }

    /**
     * Validates the input fields if it matches the fields in the model
     * and then inserts the record into the database
     * @param $params array
     * @return mixed
     * @throws Exception
     */
    public static function create(array $params): array
    {
        $model = new static();
        $model->validate_fields($params);
        return $model->insert($params);
    }

    public function validate_fields($params): void
    {
        foreach ($this->fields as $field) {
            if (!in_array($field, array_keys($params))) {
                throw new Exception("Field {$field} is required");
            }
        }
    }

    private function insert($params): array
    {
        $db = DatabaseQuery::new();
        $query = "INSERT INTO {$this->table} (";
        $query .= implode(',', array_keys($params));
        $query .= ") VALUES (";
        $query .= implode(',', array_fill(0, count($params), '?'));
        $query .= ")";
        $db->setQuery($query);
        $db->setParameters(array_values($params));
        $db->execute();
        $params[$this->primaryKey] = $db->lastInsertId($this->table);
        return $params;
    }

    /**
     * "Hydrates" the object to the model
     * throws an exception if the fields are not valid for the model
     * @param $params array | stdClass
     * @return Elegant
     * @throws Exception
     */
    public static function hydrate(array|stdClass &$params): static
    {
        $hydratedClass = new static();
        $params = (array)$params;
        // validate the parameters
        $hydratedClass->validate_fields($params);
        foreach ($params as $param => $value) {
            $hydratedClass->{$param} = $value;
        }
        if (isset($params[$hydratedClass->primaryKey])) {
            $hydratedClass->exists = true;
            $hydratedClass->id = $params[$hydratedClass->primaryKey];
        }
        $params = $hydratedClass;
        return $hydratedClass;
    }

    /**
     * Retrieve the primary key of the model
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * Adds a hasMany relationship to the model
     * @param $model_class
     * @throws Exception
     */
    public function hasMany($model_class, $foreignKeyName, $table = null, $instance = null): HasMany
    {
        $this->validate_class($model_class);
        return new HasMany($model_class, $foreignKeyName, $table, $instance);
    }

    /**
     * Adds a belongsTo relationship to the model
     * @throws ReflectionException
     */
    public function belongsTo($model_class, $foreignKeyName, $localKeyName, $table = null, $instance = null): BelongsTo
    {
        $this->validate_class($model_class);
        return new BelongsTo($model_class, $foreignKeyName, $localKeyName, $table, $instance);
    }

    /**
     * Adds a hasOne relationship to the model
     * @throws Exception
     */
    public function hasOne($model_class)
    {
        $this->validate_class($model_class);
    }

    /**
     * Validate if the model extends the Elegant class
     * @param $model_class
     * @throws Exception
     */
    private function validate_class($model_class): void
    {
        // check if the class extends Elegant
        if (!is_subclass_of($model_class, Elegant::class)) {
            throw new Exception("The class {$model_class} does not extend Elegant");
        }
    }

    /**
     * Retrieves a single record from the database
     * based on the id or primary key, if no record is found
     * it will kill the application
     * @param int $id
     * @return stdClass|void
     * @throws Exception
     */
    public function findOrFail($id)
    {
        if (!is_numeric($id)) {
            throw new Exception("The id must be numeric");
        }
        return $this->find($id);

    }

    /**
     * Retrieves all records from the database
     * and returns them as an array
     * @return array
     * @throws Exception
     */
    public function get(): array
    {
        $baseQuery = "SELECT * FROM {$this->table} ";
        $baseQuery .= $this->query;
        $db = DatabaseQuery::new();
        $db->setQuery($baseQuery);
        $db->setParameters($this->parameters);

        $results = $db->execute();

        if (empty($results)) {
            throw new Exception("No records found");
        }

        $this->get_relations($results);
        return $results;
    }

    public function first() : stdClass
    {
        $baseQuery = "SELECT * FROM {$this->table} ";
        $baseQuery .= $this->query;
        $db = DatabaseQuery::new();
        $db->setQuery($baseQuery);
        $db->setParameters($this->parameters);

        $results = $db->execute();

        if (empty($results)) {
            throw new Exception("No records found");
        }

        $this->get_relations($results);

        return $results[0];
    }


    /**
     * @throws Exception
     */
    public function update($parameters){
        // validate the parameters
        $this->validate_fields($parameters);
        $db = DatabaseQuery::new();
        $query = "UPDATE {$this->table} SET ";
        $query .= implode(' = ?, ', array_keys($parameters));
        $query .= " = ? WHERE {$this->primaryKey} = ?";
        $db->setQuery($query);
        $db->setParameters(array_merge(array_values($parameters), [$this->id]));
        $db->execute();
        return $this;
    }

    /**
     * @throws Exception
     */
    public function delete()
    {
        $db = DatabaseQuery::new();
        $query = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $db->setQuery($query);
        $db->setParameters([$this->id]);
        $db->execute();
        return $this;
    }
}