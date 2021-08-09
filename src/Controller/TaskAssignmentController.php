<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Taskassignment Controller
 *
 * @property \App\Model\Table\TaskassignmentTable $Taskassignment
 * @method \App\Model\Entity\Taskassignment[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TaskassignmentController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Manager', 'Task'],
        ];
        $taskassignment = $this->paginate($this->Taskassignment);

        $this->set(compact('taskassignment'));
    }

    /**
     * View method
     *
     * @param string|null $id Taskassignment id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $taskassignment = $this->Taskassignment->get($id, [
            'contain' => ['Manager', 'Task'],
        ]);

        $this->set(compact('taskassignment'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $taskassignment = $this->Taskassignment->newEmptyEntity();
        if ($this->request->is('post')) {
            $taskassignment = $this->Taskassignment->patchEntity($taskassignment, $this->request->getData());
            if ($this->Taskassignment->save($taskassignment)) {
                $this->Flash->success(__('The taskassignment has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The taskassignment could not be saved. Please, try again.'));
        }
        $manager = $this->Taskassignment->Manager->find('list', ['limit' => 200]);
        $task = $this->Taskassignment->Task->find('list', ['limit' => 200]);
        $this->set(compact('taskassignment', 'manager', 'task'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Taskassignment id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $taskassignment = $this->Taskassignment->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $taskassignment = $this->Taskassignment->patchEntity($taskassignment, $this->request->getData());
            if ($this->Taskassignment->save($taskassignment)) {
                $this->Flash->success(__('The taskassignment has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The taskassignment could not be saved. Please, try again.'));
        }
        $manager = $this->Taskassignment->Manager->find('list', ['limit' => 200]);
        $task = $this->Taskassignment->Task->find('list', ['limit' => 200]);
        $this->set(compact('taskassignment', 'manager', 'task'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Taskassignment id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $taskassignment = $this->Taskassignment->get($id);
        if ($this->Taskassignment->delete($taskassignment)) {
            $this->Flash->success(__('The taskassignment has been deleted.'));
        } else {
            $this->Flash->error(__('The taskassignment could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
