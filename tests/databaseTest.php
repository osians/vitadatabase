<?php

require_once __DIR__ . '/../src/Factory.php';

class VitaDatabaseTest extends PHPUnit\Framework\TestCase
{
    private $databasePath;

    private $database;

    public function setUp()
    {
        $this->databasePath = __DIR__.'/logs';
        
        $this->mysql = Osians\Database\Factory::create('MySQL', array(
            'hostname' => '127.0.0.1',
            'port' => '3306',
            'username' => 'osians',
            'password' => '123456',
            'database' => 'vita'
        ));


        // $this->database = Osians\Database\Factory::create('MySQL');
        // $this->logger = new Logit($this->databasePath, LogitLevel::DEBUG, array ('flushFrequency' => 1));
        // $this->errLogger = new Logit($this->databasePath, LogitLevel::ERROR, array (
            // 'extension' => 'log',
            // 'prefix' => 'error_',
            // 'flushFrequency' => 1
        // ));
    }

    public function testConexaoManualMySQL()
    {
        $mysql = Osians\Database\Factory::create('MySQL', array(
            'hostname' => '127.0.0.1',
            'port' => '3306',
            'username' => 'osians',
            'password' => '123456',
            'database' => 'vita'
        ));
        $this->assertInstanceOf('\Osians\Database\Db', $mysql);
    }

    public function testeConexaoManualSqlite()
    {
        $sqlite = Osians\Database\Factory::create('SQLite', array(
            'dbpath' => __DIR__ . '/database/',
            'dbname' => 'databasetest.sqlite'
        ));
        $this->assertInstanceOf('\Osians\Database\Db', $sqlite);
    }

    public function testConsultaMysql()
    {
        $this->mysql->query(
            "SELECT id_categoria, nome, id_parent, ativo 
             FROM categoria 
             WHERE id_categoria = :id_categoria"
        );
        $this->mysql->bind(':id_categoria', '1');
        $row = $this->mysql->single();

        $this->assertArrayHasKey('id_categoria', $row);
    }

    public function testInsertMysql()
    {
        # Inserindo registros ...
        $this->mysql->query('INSERT INTO categoria (id_categoria, nome, id_parent, ativo) VALUES (:id_categoria, :nome, :id_parent, :ativo)');

        $this->mysql->bind(':id_categoria', null);
        $this->mysql->bind(':nome', 'Test ' . date('D, d M Y H:i:s'));
        $this->mysql->bind(':id_parent', null);
        $this->mysql->bind(':ativo', 1);

        $this->mysql->execute();

        $this->assertGreaterThanOrEqual(0, $this->mysql->lastInsertId());
    }


    // public function testImplementsPsr3LoggerInterface()
    // {
    //     $this->assertInstanceOf('\Osians\Logit\LogitInterface', $this->logger);
    // }

    // public function testAcceptsExtension()
    // {
    //     $this->assertStringEndsWith('.log', $this->errLogger->getLogFilePath());
    // }

    // public function testAcceptsPrefix()
    // {
    //     $filename = basename($this->errLogger->getLogFilePath());
    //     $this->assertStringStartsWith('error_', $filename);
    // }

    // public function testWritesBasicLogs()
    // {
    //     $this->logger->log(LogitLevel::DEBUG, 'This is a test');
    //     $this->errLogger->log(LogitLevel::ERROR, 'This is a test');

    //     $this->assertTrue(file_exists($this->errLogger->getLogFilePath()));
    //     $this->assertTrue(file_exists($this->logger->getLogFilePath()));

    //     $this->assertLastLineEquals($this->logger);
    //     $this->assertLastLineEquals($this->errLogger);
    // }


    // public function assertLastLineEquals(Logit $logr)
    // {
    //     $this->assertEquals($logr->getLastLogLine(), $this->getLastLine($logr->getLogFilePath()));
    // }

    // public function assertLastLineNotEquals(Logit $logr)
    // {
    //     $this->assertNotEquals($logr->getLastLogLine(), $this->getLastLine($logr->getLogFilePath()));
    // }

    // private function getLastLine($filename)
    // {
    //     $size = filesize($filename);
    //     $fp = fopen($filename, 'r');
    //     $pos = -2; // start from second to last char
    //     $t = ' ';

    //     while ($t != "\n") {
    //         fseek($fp, $pos, SEEK_END);
    //         $t = fgetc($fp);
    //         $pos = $pos - 1;
    //         if ($size + $pos < -1) {
    //             rewind($fp);
    //             break;
    //         }
    //     }

    //     $t = fgets($fp);
    //     fclose($fp);

    //     return trim($t);
    // }

    public function tearDown() {
        #@unlink($this->logger->getLogFilePath());
        #@unlink($this->errLogger->getLogFilePath());
    }
}

