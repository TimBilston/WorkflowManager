<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Taskassignment Model
 *
 * @property \App\Model\Table\ManagerTable&\Cake\ORM\Association\BelongsTo $Manager
 * @property \App\Model\Table\TaskTable&\Cake\ORM\Association\BelongsTo $Task
 *
 * @method \App\Model\Entity\Taskassignment newEmptyEntity()
 * @method \App\Model\Entity\Taskassignment newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Taskassignment[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Taskassignment get($primaryKey, $options = [])
 * @method \App\Model\Entity\Taskassignment findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Taskassignment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Taskassignment[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Taskassignment|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Taskassignment saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Taskassignment[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Taskassignment[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Taskassignment[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Taskassignment[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class TaskassignmentTable extends Table
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

        $this->setTable('taskassignment');

        $this->belongsTo('Manager', [
            'foreignKey' => 'manager_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Task', [
            'foreignKey' => 'task_id',
            'joinType' => 'INNER',
        ]);
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
        $rules->add($rules->existsIn(['manager_id'], 'Manager'), ['errorField' => 'manager_id']);
        $rules->add($rules->existsIn(['task_id'], 'Task'), ['errorField' => 'task_id']);

        return $rules;
    }
}
