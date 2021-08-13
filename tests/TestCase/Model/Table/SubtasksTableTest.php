<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SubtasksTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SubtasksTable Test Case
 */
class SubtasksTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SubtasksTable
     */
    protected $Subtasks;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Subtasks',
        'app.Tasks',
        'app.Status',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Subtasks') ? [] : ['className' => SubtasksTable::class];
        $this->Subtasks = $this->getTableLocator()->get('Subtasks', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Subtasks);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\SubtasksTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\SubtasksTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
