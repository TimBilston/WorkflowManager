<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Clients Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\TasksTable&\Cake\ORM\Association\HasMany $Tasks
 *
 * @method \App\Model\Entity\Client newEmptyEntity()
 * @method \App\Model\Entity\Client newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Client[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Client get($primaryKey, $options = [])
 * @method \App\Model\Entity\Client findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Client patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Client[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Client|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Client saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Client[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Client[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Client[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Client[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class ClientsTable extends Table
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

        $this->setTable('clients');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'employee_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Tasks', [
            'foreignKey' => 'client_id',
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
            ->scalar('name')
            ->maxLength('name', 20)
            ->requirePresence('name', 'create')
            ->notEmptyString('name')
            ->add('name', [
                'nosymbol' => [
                    'rule' => ['custom', '/^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-, ])*$/'],
                    'message' => 'Name can not have any symbols',
                ]
            ]);

        $validator
            ->scalar('company_name')
            ->maxLength('company_name', 20)
            ->requirePresence('company_name', 'create')
            ->notEmptyString('company_name')
            ->add('company_name', [
                'nosymbol' => [
                    'rule' => ['custom', '/^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-, ])*$/'],
                    'message' => 'Company name can not have any symbols',
            ]
        ]);

        $validator
            ->scalar('phone')
            ->maxLength('phone', 10)
            ->requirePresence('phone', 'create')
            ->notEmptyString('phone')
            ->numeric('phone');

        $validator
            ->email('email')
            ->maxLength('email', 20)
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

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

        return $rules;
    }
}
