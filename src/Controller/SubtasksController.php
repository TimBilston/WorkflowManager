<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Subtasks Controller
 *
 * @property \App\Model\Table\SubtasksTable $Subtasks
 * @method \App\Model\Entity\Subtask[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SubtasksController extends AppController
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
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Tasks', 'Status'],
        ];
        $subtasks = $this->paginate($this->Subtasks);

        $this->set(compact('subtasks'));
    }

    /**
     * View method
     *
     * @param string|null $id Subtask id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $subtask = $this->Subtasks->get($id, [
            'contain' => ['Tasks', 'Status'],
        ]);

        $this->set(compact('subtask'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $subtask = $this->Subtasks->newEmptyEntity();
        if ($this->request->is('post')) {
            $subtask = $this->Subtasks->patchEntity($subtask, $this->request->getData());
            if ($this->Subtasks->save($subtask)) {
                $this->Flash->success(__('The subtask has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            //$this->Flash->error(__('The subtask could not be saved. Please, try again.'));
        }
        $tasks = $this->Subtasks->Tasks->find('list', ['limit' => 200]);
        $status = $this->Subtasks->Status->find('list', ['limit' => 200]);
        $this->set(compact('subtask', 'tasks', 'status'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Subtask id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $subtask = $this->Subtasks->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $subtask = $this->Subtasks->patchEntity($subtask, $this->request->getData());
            if ($this->Subtasks->save($subtask)) {
                $this->Flash->success(__('The subtask has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The subtask could not be saved. Please, try again.'));
        }
        $tasks = $this->Subtasks->Tasks->find('list', ['limit' => 200]);
        $status = $this->Subtasks->Status->find('list', ['limit' => 200]);
        $this->set(compact('subtask', 'tasks', 'status'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Subtask id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $subtask = $this->Subtasks->get($id);
        if ($this->Subtasks->delete($subtask)) {
            $this->Flash->success(__('The subtask has been deleted.'));
        } else {
            $this->Flash->error(__('The subtask could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
