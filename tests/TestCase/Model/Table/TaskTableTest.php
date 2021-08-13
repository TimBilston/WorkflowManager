<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TaskTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TaskTable Test Case
 */
class TaskTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TaskTable
     */
    protected $Task;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Task',
        'app.Employee',
        'app.TaskAssignment',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Task') ? [] : ['className' => TaskTable::class];
        $this->Task = $this->getTableLocator()->get('Task', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Task);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\TaskTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\TaskTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
