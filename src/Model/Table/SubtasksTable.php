<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Subtasks Model
 *
 * @property \App\Model\Table\TasksTable&\Cake\ORM\Association\BelongsTo $Tasks
 * @property \App\Model\Table\StatusTable&\Cake\ORM\Association\BelongsTo $Status
 *
 * @method \App\Model\Entity\Subtask newEmptyEntity()
 * @method \App\Model\Entity\Subtask newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Subtask[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Subtask get($primaryKey, $options = [])
 * @method \App\Model\Entity\Subtask findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Subtask patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Subtask[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Subtask|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Subtask saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Subtask[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Subtask[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Subtask[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Subtask[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class SubtasksTable extends Table
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

        $this->setTable('subtasks');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsTo('Tasks', [
            'foreignKey' => 'task_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Status', [
            'foreignKey' => 'status_id',
            'joinType' => 'INNER',
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

//        $validator
//            ->scalar('title')
//            ->maxLength('title', 30, "The task's title is too long", 'update')
//            ->requirePresence('title', 'create')
//            ->notEmptyString('title')
//            ->add('title', [
//                'nosymbol' => [
//                    'rule' => ['custom', '/^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-, ])*$/'],
//                    'message' => 'The title can not have any symbols',
//                ]
//            ]);
//
//        $validator
//            ->date('start_date')
//            ->requirePresence('start_date', 'create')
//            ->notEmptyDate('start_date');
//
//        $validator
//            ->date('due_date')
//            ->requirePresence('due_date', 'create')
//            ->notEmptyDate('due_date')
//            ->add('due_date', 'dateCompare', [
//                'rule' => 'dateCompare',
//                'provider' => 'table',
//                'message' => 'Due date cannot be before start date'
//            ]);

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
        $rules->add($rules->existsIn(['task_id'], 'Tasks'), ['errorField' => 'task_id']);
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
