<?php

namespace Osians\Database;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Provider/Mysql.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Provider/Sqlite.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Db.php';

class Factory
{
    /**
     *    Constroi a Conexao para um Banco de Dados, dado seu Nome
     *
     *    @param string $drive - Tipo de banco de dados (Mysql, Sqlite)
     *    @param array $parametros - dados de conexao
     *    @return object - Objeto de conexao ao DB escolhido via PDO
     */
    public static function create($drive = 'MySQL', $parametros = array())
    {
        switch (strtolower($drive)) {
            case 'mysql':

                $drive = new \Osians\Database\Provider\Mysql();

                if (isset($parametros['hostname']) &&
                    isset($parametros['port']) &&
                    isset($parametros['username']) &&
                    isset($parametros['password']) &&
                    isset($parametros['database'])
               ) {
                    $drive->setHost($parametros['hostname']);
                    $drive->setDbport($parametros['port']);
                    $drive->setUser($parametros['username']);
                    $drive->setPass($parametros['password']);
                    $drive->setDbname($parametros['database']);
                }

                $conn = $drive->conectar();
                return new \Osians\Database\Db($conn);
            break;

            case 'sqlite':
                $drive = new \Osians\Database\Provider\Sqlite();

                if (isset($_extra_args['dbpath']) && isset($_extra_args['dbname'])) {
                    $drive->setDatabasePath($_extra_args['dbpath']);
                    $drive->setDataBaseName($_extra_args['dbname']);
                }

                $conn = $drive->conectar();
                return new \Osians\Database\Db($conn);
            break;

            default:
                throw new \Exception("Provedor desconhecido: {$drive}", 1);
            break;
        }
    }
}
