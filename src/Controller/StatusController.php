<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Status Controller
 *
 * @property \App\Model\Table\StatusTable $Status
 * @method \App\Model\Entity\Status[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StatusController extends AppController
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
//    /**
//     * Index method
//     *
//     * @return \Cake\Http\Response|null|void Renders view
//     */
//    public function index()
//    {
//        $status = $this->paginate($this->Status);
//        $this->set(compact('status'));
//    }

    /**
     * View method
     *
     * @param string|null $id Status id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $status = $this->Status->get($id, [
            'contain' => ['Subtasks', 'Tasks'],
        ]);

        $this->set(compact('status'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $status = $this->Status->newEmptyEntity();
        $this->Authorization->authorize($status);
        if ($this->request->is('post')) {
            $status = $this->Status->patchEntity($status, $this->request->getData());
            if ($this->Status->save($status)) {
                $this->Flash->success(__('The status has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The status could not be saved. Please, try again.'));
        }
        $this->set(compact('status'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Status id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $status = $this->Status->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $status = $this->Status->patchEntity($status, $this->request->getData());
            $this->Authorization->authorize($status);
            if ($this->Status->save($status)) {
                $this->Flash->success(__('The status has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The status could not be saved. Please, try again.'));
        }
        $this->set(compact('status'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Status id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $status = $this->Status->get($id);
        $this->Authorization->authorize($status);
        if ($this->Status->delete($status)) {
            $this->Flash->success(__('The status has been deleted.'));
        } else {
            $this->Flash->error(__('The status could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
