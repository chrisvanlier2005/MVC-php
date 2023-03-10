<?php
namespace Core\Database\Relations;


use Core\Database\DatabaseQuery;

class HasMany extends \Core\Database\Relations\BaseRelation
{

    public function execute_relation(&$result, QueryType $type, $relation_primary_key = "id"){
        if ($type == 1)
        {
            return $this->fetchSingle($result);
        }
        return $this->fetchMultiple($result, $relation_primary_key);
    }

    protected function fetchMultiple(&$results, $relation_primary_key)
    {
        $parentIds = [];
        $query = "SELECT * FROM {$this->table} WHERE {$this->foreignKeyName} IN (";
        foreach ($results as $result)
        {
            $parentIds[] = $result->$relation_primary_key;
        }
        $query .= implode(',', $parentIds);
        $query .= ")";
        $db = DatabaseQuery::new();
        $db->setQuery($query);
        $manyResults = $db->execute();

        $this->sort_items($results, $manyResults, $relation_primary_key);
    }

    protected function fetchSingle(&$result){
        $query = "SELECT * FROM {$this->table} WHERE {$this->foreignKeyName} = ?";
        $output = $this->single_query($query, [$result->id]);
        $result->{$this->table} = $output;
    }
    public function fetch(){
        $query = "SELECT * FROM {$this->table} WHERE {$this->foreignKeyName} = ?";
        return $this->single_query($query, [$this->id]);
    }

}