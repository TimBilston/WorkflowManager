<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class TaskTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('task');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('title')
            ->maxLength('title', 100, 'The title is too long', 'update')
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->decimal('description')
            ->requirePresence('description', 'create')
            ->notEmptyString('description')
            ->maxLength('description', 100)
            ->notAlphaNumeric('description', 'description can not be negative', 'update');

        $validator
            ->scalar('start_date')
            ->requirePresence('start_date', 'create');

        $validator
            ->scalar('due_date')
            ->maxLength('due_date', 25, 'due_date is too long', 'update')
            ->requirePresence('due_date', 'create')
            ->notEmptyString('due_date');

        $validator
            ->scalar('employee_id ')
            ->maxLength('employee_id ', 100)
            ->requirePresence('employee_id ', 'create')
            ->notEmptyString('employee_id ');

        $validator
            ->scalar('recurring')
            ->maxLength('recurring', 255)
            ->requirePresence('recurring', 'create')
            ->notEmptyFile('recurring');

        $validator
            ->scalar('role_type')
            ->maxLength('role_type', 100)
            ->requirePresence('role_type', 'create')
            ->notEmptyFile('role_type');
        return $validator;
    }
}
