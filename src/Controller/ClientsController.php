<?php
declare(strict_types=1);

namespace App\Controller;

use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;

/**
 * Clients Controller
 *
 * @property \App\Model\Table\ClientsTable $Clients
 * @method \App\Model\Entity\Client[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ClientsController extends AppController
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

    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->Authorization->skipAuthorization();

        $this->paginate = [
            'contain' => ['Users'],
        ];
        $clients = $this->paginate($this->Clients);

        $this->set(compact('clients'));
    }

    /**
     * View method
     *
     * @param string|null $id Client id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->Authorization->skipAuthorization();

        $client = $this->Clients->get($id, [
            'contain' => ['Users', 'Tasks'],
        ]);

        $users = $this->Clients->Users->find('list', ['limit' => 200]);
        //$task = $this->Clients->Tasks->find('list', ['limit' => 200]);
        $departments = $this->Clients->Tasks->Departments->find('list', ['limit' => 200]);
        $clients = $this->Clients->find('list', ['limit' => 200]);
        $status = $this->Clients->Tasks->Status->find('list', ['limit' => 200]);
        $this->set(compact('client', 'users', 'departments', 'clients', 'status'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->Authorization->skipAuthorization();
        $client = $this->Clients->newEmptyEntity();

        if ($this->request->is('post')) {
            $client = $this->Clients->patchEntity($client, $this->request->getData());
            if ($this->Clients->save($client)) {
                $this->Flash->success(__('The client has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The client could not be saved. Please, try again.'));
        }
        $users = $this->Clients->Users->find('list', ['limit' => 200]);
        //$task = $this->Clients->Tasks->find('list', ['limit' => 200]);
        $departments = $this->Clients->Tasks->Departments->find('list', ['limit' => 200]);
        $clients = $this->Clients->find('list', ['limit' => 200]);
        $status = $this->Clients->Tasks->Status->find('list', ['limit' => 200]);
        $this->set(compact('client', 'users', 'departments', 'clients', 'status'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Client id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $client = $this->Clients->newEmptyEntity();
        $this->Authorization->authorize($client);


        $tasksTable = $this->loadModel('Tasks');

        $client = $this->Clients->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $client = $this->Clients->patchEntity($client, $this->request->getData());
            if ($this->Clients->save($client)) {
                $this->Flash->success(__('The client has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The client could not be saved. Please, try again.'));
        }
        $users = $this->Clients->Users->find('list', ['limit' => 200]);
        //$task = $this->Clients->Tasks->find('list', ['limit' => 200]);
        $departments = $this->Clients->Tasks->Departments->find('list', ['limit' => 200]);
        $clients = $this->Clients->find('list', ['limit' => 200]);
        $status = $this->Clients->Tasks->Status->find('list', ['limit' => 200]);
        $this->set(compact('client', 'users', 'departments', 'clients', 'status'));
    }
    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function addTask()
    {
        $this->Authorization->skipAuthorization();

        $tasksTable = \Cake\ORM\TableRegistry::getTableLocator()->get('Tasks');;
        $task = $tasksTable->newEmptyEntity();
        if ($this->request->is('post')) {
            $task = $this->Tasks->patchEntity($task, $this->request->getData());
            if ($this->Tasks->save($task)) {
                $this->Flash->success(__('The task has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The task could not be saved. Please, try again.'));
        }
        $users = $this->Clients->Tasks->Users->find('list', ['limit' => 200]);
        $departments = $this->Clients->Tasks->Departments->find('list', ['limit' => 200]);
        $clients = $this->Clients->Tasks->find('list', ['limit' => 200]);
        $status = $this->Clients->Tasks->Status->find('list', ['limit' => 200]);
        $this->set(compact('task', 'users', 'departments', 'clients', 'status'));
    }
    /**
     * Delete method
     *
     * @param string|null $id Client id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $client = $this->Clients->newEmptyEntity();
        $this->Authorization->authorize($client);


        $this->request->allowMethod(['post', 'delete']);
        $client = $this->Clients->get($id);
        if ($this->Clients->delete($client)) {
            $this->Flash->success(__('The client has been deleted.'));
        } else {
            $this->Flash->error(__('The client could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
