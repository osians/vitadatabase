<?php

namespace Osians\Database\Provider;

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'ProviderInterface.php';

class Sqlite implements \Osians\Database\ProviderInterface
{
    private $sqliteDbFolder = null;
    private $sqliteDbname = null;
    private $dbh = null;

    public function __construct(
        $sqliteDbFolder = null,
        $sqliteDbname = null
    ) {
        if ($sqliteDbFolder != null) {
            $this->sqliteDbFolder = $sqliteDbFolder;
        }
        if ($sqliteDbname  != null) {
            $this->sqliteDbname  = $sqliteDbname;
        }
    }

    public function setDatabasePath($value){$this->sqliteDbFolder = $value;}
    public function setDataBaseName($value){$this->sqliteDbname = $value;}

    public function conectar()
    {
        $dsn = "sqlite:{$this->sqliteDbFolder}{$this->sqliteDbname}";

        try {
            $this->dbh = new \PDO($dsn);
            $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            //$memory_db = new \PDO('sqlite::memory:');
            //$memory_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $this->dbh;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }
}