<?php

use Carbon\Carbon;
use Cartrack\Db\Database;
use Phinx\Console\PhinxApplication;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class DatabaseTestCase extends TestCase
{
    protected $db;

    protected $tables = [
        'tweets',
        'sentiments',
        'tweets_sentiments'
    ];

    public function tearDown(): void
    {
        $this->truncateTables();
        parent::tearDown();
    }

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        global $config;
        Database::connect($config['database']['testing']);
        $this->db = Database::getInstance();
        Carbon::setTestNow(Carbon::now());
        $this->setUpDatabase();
        parent::__construct($name, $data, $dataName);
    }

    protected function setUpDatabase(): void
    {
        $app = new PhinxApplication();

        $app->setAutoExit(false);
        $app->run(new StringInput('migrate'), new ConsoleOutput());
        $this->truncateTables();
    }

    private function truncateTables(): void
    {
        foreach ($this->tables as $table) {
            $this->db->exec("TRUNCATE table ". $table . ' RESTART IDENTITY');
        }
    }
}