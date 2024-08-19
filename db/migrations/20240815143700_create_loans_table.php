<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateLoansTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('loans');
        $table->addColumn('name', 'string', ['limit' => 100, 'null' => false])
              ->addColumn('ktp', 'string', ['limit' => 20, 'null' => false])
              ->addColumn('loan_amount', 'integer', ['limit' => 10000, 'null' => false])
              ->addColumn('loan_period', 'integer', ['null' => false])
              ->addColumn('loan_purpose', 'string', ['null' => false])
              ->addColumn('sex', 'string', ['limit' => 1, 'null' => false])
              ->addColumn('date_of_birth', 'timestamp', ['null' => false])
              ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->create();

        // Add unique constraint to ktp column
        $table->addIndex(['ktp'], ['unique' => true])
              ->update();
    }
}
