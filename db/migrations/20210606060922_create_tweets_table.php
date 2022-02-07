<?php
use Phinx\Migration\AbstractMigration;

class CreateTweetsTable extends AbstractMigration
{
    public function change()
    {
        $this->table('tweets')
            ->addColumn('user_id', 'integer')
            ->addColumn('body', 'string')
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->addIndex('user_id')
            ->create();
    }
}
