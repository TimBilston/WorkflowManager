<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Recurrences Model
 *
 * @property \App\Model\Table\TasksTable&\Cake\ORM\Association\HasMany $Tasks
 *
 * @method \App\Model\Entity\Recurrence newEmptyEntity()
 * @method \App\Model\Entity\Recurrence newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Recurrence[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Recurrence get($primaryKey, $options = [])
 * @method \App\Model\Entity\Recurrence findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Recurrence patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Recurrence[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Recurrence|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Recurrence saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Recurrence[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Recurrence[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Recurrence[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Recurrence[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class RecurrencesTable extends Table
{
    /**
     * Before Filter method
     *
     * Handles the authentication of certain pages
     *
     * @param \Cake\Event\EventInterface $event
     * @return Response|void|null
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // Configure the login action to not require authentication, preventing
        // the infinite redirect loop issue
        //$this->Authentication->addUnauthenticatedActions();
        $this->Authorization->skipAuthorization();
    }
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('recurrences');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('Tasks', [
            'foreignKey' => 'recurrence_id',
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
            ->scalar('recurrence')
            ->requirePresence('recurrence', 'create')
            ->notEmptyString('recurrence');

        $validator
            ->integer('no_of_recurrence')
            ->requirePresence('no_of_recurrence', 'create')
            ->notEmptyString('no_of_recurrence');

        return $validator;
    }
}
