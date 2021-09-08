<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Tasks Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\DepartmentsTable&\Cake\ORM\Association\BelongsTo $Departments
 * @property \App\Model\Table\ClientsTable&\Cake\ORM\Association\BelongsTo $Clients
 * @property \App\Model\Table\StatusTable&\Cake\ORM\Association\BelongsTo $Status
 * @property \App\Model\Table\SubtasksTable&\Cake\ORM\Association\HasMany $Subtasks
 *
 * @method \App\Model\Entity\Task newEmptyEntity()
 * @method \App\Model\Entity\Task newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Task[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Task get($primaryKey, $options = [])
 * @method \App\Model\Entity\Task findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Task patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Task[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Task|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Task saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Task[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Task[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Task[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Task[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class TasksTable extends Table
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

        $this->setTable('tasks');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'employee_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Departments', [
            'foreignKey' => 'department_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Clients', [
            'foreignKey' => 'client_id',
        ]);
        $this->belongsTo('Status', [
            'foreignKey' => 'status_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Subtasks', [
            'foreignKey' => 'task_id',
        ]);
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
            ->maxLength('title', 30, "The task's title is too long", 'update')
            ->requirePresence('title', 'create')
            ->notEmptyString('title')
            ->add('title', [
                'nosymbol' => [
                    'rule' => ['custom', '/^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-, ])*$/'],
                    'message' => 'Title can not be empty or have any symbols',
                ]
            ]);

        $validator
            ->scalar('description')
            ->maxLength('description', 100,'The description is too long', 'update')
            ->requirePresence('description', 'create')
            ->notEmptyString('description')
            ->add('description', [
                'nosymbol' => [
                    'rule' => ['custom', '/^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-, ])*$/'],
                    'message' => 'Description can not have any symbols',
                ]
            ]);

        $validator
            ->date('start_date')
            ->requirePresence('start_date', 'create')
            ->notEmptyDate('start_date');

        $validator
            ->date('due_date')
            ->requirePresence('due_date', 'create')
            ->notEmptyDate('due_date')
            ->add('due_date', 'dateCompare', [
                'rule' => 'dateCompare',
                'provider' => 'table',
                'message' => 'Due date cannot be before start date'
            ]);

        $validator
            ->scalar('recurrence')
            ->requirePresence('recurrence', 'create')
            ->notEmptyString('recurrence');

        $validator
            ->integer('no_of_recurrence')
            ->requirePresence('no_of_recurrence', 'create')
            ->notEmptyString('no_of_recurrence')
            ->add('no_of_recurrence', [
                'nosymbol' => [
                    'rule' => ['custom', '/^\d+$/'],
                    'message' => 'Can only have positive whole integers',
                ]
            ]);

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['employee_id'], 'Users'), ['errorField' => 'employee_id']);
        $rules->add($rules->existsIn(['department_id'], 'Departments'), ['errorField' => 'department_id']);
        $rules->add($rules->existsIn(['client_id'], 'Clients'), ['errorField' => 'client_id']);
        $rules->add($rules->existsIn(['status_id'], 'Status'), ['errorField' => 'status_id']);

        return $rules;
    }

    public function dateCompare($value, $context){
        if ($context['data']['start_date'] > $value){
            return false;
        }
        return true;
    }
}
