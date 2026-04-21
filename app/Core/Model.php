<?php
namespace App\Core;

class Model
{
    protected $pdo;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function find($id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function findAll($orderBy = 'id DESC', $limit = null, $offset = null)
    {
        $sql = "SELECT * FROM `{$this->table}` ORDER BY {$orderBy}";
        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;
            if ($offset !== null) {
                $sql .= " OFFSET " . (int)$offset;
            }
        }
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $columns = implode('`, `', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO `{$this->table}` (`{$columns}`) VALUES ({$placeholders})";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "`{$key}` = :{$key}";
        }
        $setStr = implode(', ', $set);
        $sql = "UPDATE `{$this->table}` SET {$setStr} WHERE `{$this->primaryKey}` = :_pk_id";
        $data['_pk_id'] = $id;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id"
        );
        return $stmt->execute(['id' => $id]);
    }

    public function count($where = '', $params = [])
    {
        $sql = "SELECT COUNT(*) as total FROM `{$this->table}`";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch()['total'];
    }

    public function where($conditions, $params = [], $orderBy = 'id DESC', $limit = null, $offset = null)
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE {$conditions} ORDER BY {$orderBy}";
        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;
            if ($offset !== null) {
                $sql .= " OFFSET " . (int)$offset;
            }
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function paginate($page = 1, $perPage = 15, $where = '', $params = [], $orderBy = 'id DESC')
    {
        $total = $this->count($where, $params);
        $totalPages = max(1, ceil($total / $perPage));
        $page = max(1, min($page, $totalPages));
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT * FROM `{$this->table}`";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        $sql .= " ORDER BY {$orderBy} LIMIT " . (int)$perPage . " OFFSET " . (int)$offset;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll();

        return [
            'data'         => $data,
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'total_pages'  => $totalPages,
            'has_prev'     => $page > 1,
            'has_next'     => $page < $totalPages,
        ];
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function queryOne($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    public function execute($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}
