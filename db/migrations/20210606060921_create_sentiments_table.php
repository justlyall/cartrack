<?php
use Phinx\Migration\AbstractMigration;

class CreateSentimentsTable extends AbstractMigration
{
    public function change()
    {
        $this->table('sentiments')
            ->addColumn('sentiment', 'string')
            ->addColumn('created_at', 'datetime')
            ->create();
    }
}
