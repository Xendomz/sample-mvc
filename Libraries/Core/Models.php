<?php
class Models
{

    // Property untuk koneksi ke database mysql
    private $_host = '127.0.0.1';
    private $_dbname = 'prak_pbo';
    private $_username = 'root';
    private $_password = '';

    // Property internal dari class DB
    private static $_instance = null;
    private $_pdo;
    private $_columnName = "*";
    private $_orderBy = "";
    private $_count = 0;
    protected $tableName = "";

    // Constructor untuk pembuatan PDO Object
    protected function __construct()
    {
        try {
            $this->_pdo = new PDO(
                'mysql:host=' . $this->_host . ';dbname=' . $this->_dbname,
                $this->_username,
                $this->_password
            );
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Koneksi / Query bermasalah: " . $e->getMessage() . " (" . $e->getCode() . ")");
        }
    }

    // Singleton pattern untuk membuat class DB
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new Models();
        }
        return self::$_instance;
    }

    // Method dasar untuk menjalankan prepared statement query
    public function runQuery($query, $bindValue = [])
    {
        try {
            $stmt = $this->_pdo->prepare($query);
            $stmt->execute($bindValue);
        } catch (PDOException $e) {
            die("Koneksi / Query bermasalah: " . $e->getMessage() . " (" . $e->getCode() . ")");
        }
        return $stmt;
    }

    // Method untuk menampilkan hasil query SELECT sebagai fetchAll (object)
    public function getQuery($query, $bindValue = [])
    {
        return $this->runQuery($query, $bindValue)->fetchAll(PDO::FETCH_OBJ);
    }

    // Method untuk menentukan kolom yang akan ditampilkan
    public function select($columnName)
    {
        $this->_columnName = $columnName;
        return $this;
    }

    // Method untuk menentukan urutan hasil tabel (query ORDER BY)
    public function orderBy($columnName, $sortType = 'ASC')
    {
        $this->_orderBy = "ORDER BY {$columnName} {$sortType}";
        return $this;
    }

    // Method utama untuk mengambil isi tabel
    public function get($condition = "", $bindValue = [])
    {
        $query = "SELECT {$this->_columnName} FROM {$this->tableName} {$condition} {$this->_orderBy}";
        $this->_columnName = "*";
        $this->_orderBy = "";
        return $this->getQuery($query, $bindValue);
    }

    // Method untuk mengambil isi tabel dengan kondisi WHERE
    public function getWhere($condition)
    {
        $queryCondition = "WHERE {$condition[0]} {$condition[1]} ? ";
        return $this->get($queryCondition, [$condition[2]]);
    }

    // Method untuk mengambil isi tabel dengan kondisi WHERE dan hanya baris pertama saja
    public function getWhereOnce($condition)
    {
        $result = $this->getWhere($condition);
        if (!empty($result)) {
            return $result[0];
        } else {
            return false;
        }
    }

    // Method untuk mengambil isi tabel dengan pencarian (query LIKE)
    public function getLike($columnLike, $search)
    {
        $queryLike = "WHERE {$columnLike} LIKE ?";
        return $this->get($queryLike, [$search]);
    }

    // Method untuk check nilai unik, akan berguna untuk form
    public function check($columnName, $dataValues)
    {
        $query = "SELECT {$columnName} FROM {$this->tableName} WHERE {$columnName} = ? ";
        return $this->runQuery($query, [$dataValues])->rowCount();
    }

    // Ambil nilai kolom, hasil dari rowCount()
    public function count()
    {
        return $this->_count;
    }

    // Method untuk menginput data tabel (query INSERT)
    public function insert($data)
    {
        $dataKeys = array_keys($data);
        $dataValues = array_values($data);
        $placeholder = '(' . str_repeat('?,', count($data) - 1) . '?)';

        $query = "INSERT INTO {$this->tableName} (" . implode(', ', $dataKeys) . ") VALUES {$placeholder}";
        $this->_count = $this->runQuery($query, $dataValues)->rowCount();
        return true;
    }

    // Method untuk mengupdate data tabel (query UPDATE)
    public function update($data, $condition)
    {
        $query = "UPDATE {$this->tableName} SET ";
        foreach ($data as $key => $val) {
            $query .= "$key = ?, ";
        }
        $query = substr($query, 0, -2);
        $query .= " WHERE {$condition[0]} {$condition[1]} ?";

        $dataValues = array_values($data);
        array_push($dataValues, $condition[2]);

        $this->_count = $this->runQuery($query, $dataValues)->rowCount();
        return true;
    }

    // Method untuk menghapus data tabel (query DELETE)
    public function delete($condition)
    {
        $query = "DELETE FROM {$this->tableName} WHERE {$condition[0]} {$condition[1]} ? ";
        $this->_count = $this->runQuery($query, [$condition[2]])->rowCount();
        return true;
    }
}
