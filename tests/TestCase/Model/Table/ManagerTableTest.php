<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ManagerTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ManagerTable Test Case
 */
class ManagerTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ManagerTable
     */
    protected $Manager;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Manager',
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
        $config = $this->getTableLocator()->exists('Manager') ? [] : ['className' => ManagerTable::class];
        $this->Manager = $this->getTableLocator()->get('Manager', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Manager);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\ManagerTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
