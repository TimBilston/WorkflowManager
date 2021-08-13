<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TaskassignmentFixture
 */
class TaskassignmentFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'task_assignment';
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'manager_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'task_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'manager_id' => ['type' => 'index', 'columns' => ['manager_id'], 'length' => []],
            'task_id' => ['type' => 'index', 'columns' => ['task_id'], 'length' => []],
        ],
        '_constraints' => [
            'task_assignment_ibfk_2' => ['type' => 'foreign', 'columns' => ['task_id'], 'references' => ['task', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'task_assignment_ibfk_1' => ['type' => 'foreign', 'columns' => ['manager_id'], 'references' => ['manager', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_general_ci'
        ],
    ];
    // phpcs:enable
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'manager_id' => 1,
                'task_id' => 1,
            ],
        ];
        parent::init();
    }
}
