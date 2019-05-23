<?php

class ORM
{
    protected $pdo;
    protected $table;
    protected $primaryKey;

    public function __construct(PDO $pdo, string $table, string $primaryKey)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->primaryKey = $primaryKey;
    }

    protected function query($sql, $parameters = [])
    {
        $query = $this->pdo->prepare($sql);
        $query->execute($parameters);
        return $query;
    }

    protected function processDates($fields)
    {
        foreach ($fields as $key => $value) {
            if ($value instanceof DateTime) {
                $fields[$key] = $value->format('Y-m-d H:i:s');
            }
        }

        return $fields;
    }

    protected function where($query, $whereOption)
    {
        $conditionalWord = (strpos($query, 'WHERE')) ? ' AND ' : ' WHERE ';

        if (!is_array($whereOption)) {
            $query .= $conditionalWord . 'MONTH(`date`) = ' . $whereOption->format('n') .
            ' AND YEAR(`date`) = ' . $whereOption->format('Y');
        } else {
            $query .= $conditionalWord . "`date` BETWEEN '" . $whereOption[0]->format('Y-m-d') .
            "' AND date_add('" . $whereOption[1]->format('Y-m-d') . "', INTERVAL 1 DAY)";
        }

        return $query;
    }

    protected function order($query, $sortOption)
    {
        $query .= ' ORDER BY `' . $sortOption[0] . '` ';
        $query .= (empty($sortOption[1])) ? ' ASC' : $sortOption[1];

        return $query;
    }

    protected function fetchData($query)
    {
        $query = $query->fetchAll(PDO::FETCH_ASSOC);
        $query = (!empty($query[0]) && empty($query[1])) ? $query[0] : $query;

        return $query;
    }

    public function insert($fields)
    {
        $query = 'INSERT INTO `' . $this->table . '` (';

        foreach ($fields as $key => $value) {
            $query .= '`' . $key . '`,';
        }

        $query = rtrim($query, ',');
        $query .= ') VALUES (';

        foreach ($fields as $key => $value) {
            $query .= ':' . $key . ',';
        }

        $query = rtrim($query, ',');
        $query .= ')';
        $fields = $this->processDates($fields);
        $this->query($query, $fields);

        return $this->pdo->lastInsertId();
    }

    public function update($fields)
    {
        $query = ' UPDATE `' . $this->table . '` SET ';

        foreach ($fields as $key => $value) {
            $query .= '`' . $key . '` = :' . $key . ',';
        }

        $query = rtrim($query, ',');
        $query .= ' WHERE `' . $this->primaryKey . '` = :primaryKey';
        $fields['primaryKey'] = $fields['id'];
        $fields = $this->processDates($fields);

        $this->query($query, $fields);
    }

    public function delete($id)
    {
        $parameters = [':id' => $id];
        $this->query('DELETE FROM `' . $this->table . '` WHERE `' .
            $this->primaryKey . '` = :id', $parameters);
    }

    public function read($fields, $whereOption = null, $sortOption = null)
    {
        $query = 'SELECT ';

        foreach ($fields as $field) {
            $query .= '`' . $field . '`,';
        }

        $query = rtrim($query, ',');
        $query .= ' FROM ' . $this->table;

        $query = (!is_null($whereOption)) ? $this->where($query, $whereOption) : $query;

        $query = (!is_null($sortOption)) ? $this->order($query, $sortOption) : $query;

        $query = $this->query($query);

        return $this->fetchData($query);
    }

    public function find_by_id($fields, $searchBy, $whereOption = null, $sortOption = null)
    {
        $query = 'SELECT ';
        foreach ($fields as $field) {
            $query .= '`' . $field . '`,';
        }
        $query = rtrim($query, ',');
        $value = array_values($searchBy)[0];
        $values = (is_array($value)) ? array_unique($value) : $value;
        $values = (is_array($value)) ? implode(',', array_map('intval', $value)) : $value;

        $query .= ' FROM `' . $this->table;
        $query .= '` WHERE `' . array_keys($searchBy)[0] . $this->primaryKey . '` IN(' . $values . ')';

        $query = (!is_null($whereOption)) ? $this->where($query, $whereOption) : $query;

        if (!empty(array_keys($searchBy)[1])) {
            return $query;
        }

        $query = (!is_null($sortOption)) ? $this->order($query, $sortOption) : $query;

        $query = $this->query($query);

        return $this->fetchData($query);
    }

    public function name_finder($keys_as_ids, $entity)
    {
        $entity_names = array_column($entity, 'name', 'id');

        foreach ($keys_as_ids as $key => $value) {
            $named_array[$entity_names[$key]] = $value;
        }

        return $named_array;
    }
}
