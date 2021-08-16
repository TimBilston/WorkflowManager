<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TaskassignmentTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TaskassignmentTable Test Case
 */
class TaskassignmentTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TaskassignmentTable
     */
    protected $Taskassignment;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Taskassignment',
        'app.Manager',
        'app.Task',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Taskassignment') ? [] : ['className' => TaskassignmentTable::class];
        $this->Taskassignment = $this->getTableLocator()->get('Taskassignment', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Taskassignment);

        parent::tearDown();
    }
}
