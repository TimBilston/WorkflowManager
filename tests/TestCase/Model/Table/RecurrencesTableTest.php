<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RecurrencesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RecurrencesTable Test Case
 */
class RecurrencesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\RecurrencesTable
     */
    protected $Recurrences;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Recurrences',
        'app.Tasks',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Recurrences') ? [] : ['className' => RecurrencesTable::class];
        $this->Recurrences = $this->getTableLocator()->get('Recurrences', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Recurrences);

        parent::tearDown();
    }

    /**
     * Test beforeFilter method
     *
     * @return void
     * @uses \App\Model\Table\RecurrencesTable::beforeFilter()
     */
    public function testBeforeFilter(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\RecurrencesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
