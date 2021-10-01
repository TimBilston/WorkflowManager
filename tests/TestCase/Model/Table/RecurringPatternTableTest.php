<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RecurringPatternTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RecurringPatternTable Test Case
 */
class RecurringPatternTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\RecurringPatternTable
     */
    protected $RecurringPattern;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.RecurringPattern',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('RecurringPattern') ? [] : ['className' => RecurringPatternTable::class];
        $this->RecurringPattern = $this->getTableLocator()->get('RecurringPattern', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->RecurringPattern);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\RecurringPatternTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
