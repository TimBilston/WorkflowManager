<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Manager Model
 *
 * @property \App\Model\Table\TaskAssignmentTable&\Cake\ORM\Association\HasMany $TaskAssignment
 *
 * @method \App\Model\Entity\Manager newEmptyEntity()
 * @method \App\Model\Entity\Manager newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Manager[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Manager get($primaryKey, $options = [])
 * @method \App\Model\Entity\Manager findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Manager patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Manager[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Manager|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Manager saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Manager[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Manager[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Manager[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Manager[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class ManagerTable extends Table
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

        $this->setTable('manager');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('TaskAssignment', [
            'foreignKey' => 'manager_id',
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
            ->scalar('password')
            ->maxLength('password', 64)
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $validator
            ->scalar('name')
            ->maxLength('name', 64)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 64)
            ->requirePresence('last_name', 'create')
            ->notEmptyString('last_name');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('phone')
            ->maxLength('phone', 20)
            ->requirePresence('phone', 'create')
            ->notEmptyString('phone');

        return $validator;
    }
}
