<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TasksFixture
 */
class TasksFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'title' => ['type' => 'string', 'length' => 200, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => '', 'precision' => null],
        'description' => ['type' => 'string', 'length' => 1000, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => '', 'precision' => null],
        'start_date' => ['type' => 'date', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'due_date' => ['type' => 'date', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'employee_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'recurrence_type' => ['type' => 'string', 'length' => null, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => '', 'precision' => null],
        'no_of_recurrence' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'department_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'client_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'status_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'recurrence_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'status_id' => ['type' => 'index', 'columns' => ['status_id'], 'length' => []],
            'department_id' => ['type' => 'index', 'columns' => ['department_id'], 'length' => []],
            'employee_id' => ['type' => 'index', 'columns' => ['employee_id'], 'length' => []],
            'client_id' => ['type' => 'index', 'columns' => ['client_id'], 'length' => []],
            'recurrence_id' => ['type' => 'index', 'columns' => ['recurrence_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'tasks_ibfk_5' => ['type' => 'foreign', 'columns' => ['recurrence_id'], 'references' => ['recurrences', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'tasks_ibfk_4' => ['type' => 'foreign', 'columns' => ['client_id'], 'references' => ['clients', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'tasks_ibfk_3' => ['type' => 'foreign', 'columns' => ['department_id'], 'references' => ['departments', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'tasks_ibfk_2' => ['type' => 'foreign', 'columns' => ['status_id'], 'references' => ['status', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'tasks_ibfk_1' => ['type' => 'foreign', 'columns' => ['employee_id'], 'references' => ['users', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
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
                'id' => 1,
                'title' => 'Lorem ipsum dolor sit amet',
                'description' => 'Lorem ipsum dolor sit amet',
                'start_date' => '2021-09-27',
                'due_date' => '2021-09-27',
                'employee_id' => 1,
                'recurrence_type' => 'Lorem ipsum dolor sit amet',
                'no_of_recurrence' => 1,
                'department_id' => 1,
                'client_id' => 1,
                'status_id' => 1,
                'recurrence_id' => 1,
            ],
        ];
        parent::init();
    }
}
