<?php

namespace Osians\Database;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Provider/Mysql.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Provider/Sqlite.php';

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Db.php';

class Factory
{
    /**
    * @param string  - Tipo de banco de dados a ser criado conexao
    * @return object - Objeto de conexao ao DB escolhido
    */
    public static function create($db = 'MySQL')
    {
        $_args_ = func_get_args();
        $_extra_args = isset($_args_[1]) ? $_args_[1] : null;

        $db = strtolower($db);

        switch ($db) {
            case 'mysql':

                $db = new \Osians\Database\Provider\Mysql();

                if( isset($_extra_args['hostname'])&&
                    isset($_extra_args['port'])&&
                    isset($_extra_args['username'])&&
                    isset($_extra_args['password'])&&
                    isset($_extra_args['database']) )
                {
                    $db->setHost( $_extra_args['hostname'] );
                    $db->setDbport( $_extra_args['port'] );
                    $db->setUser( $_extra_args['username'] );
                    $db->setPass( $_extra_args['password'] );
                    $db->setDbname( $_extra_args['database'] );
                }

                $conn = $db->conectar();
                return new \Osians\Database\Db($conn);
            break;

            case 'sqlite':
                $db = new \Osians\Database\Provider\Sqlite();

                if (isset($_extra_args['dbpath']) && isset($_extra_args['dbname'])) {
                    $db->setDatabasePath($_extra_args['dbpath']);
                    $db->setDataBaseName($_extra_args['dbname']);
                }

                $conn = $db->conectar();
                return new \Osians\Database\Db($conn);
            break;

            default:
                throw new \Exception("Unknown provider: {$db}", 1);
            break;
        }
    }
}
