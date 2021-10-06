<?php
declare(strict_types=1);

namespace App\Controller;

use phpDocumentor\Reflection\Types\Integer;

/**
 * Recurrences Controller
 *
 * @property \App\Model\Table\RecurrencesTable $Recurrences
 * @method \App\Model\Entity\Recurrence[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RecurrencesController extends AppController
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
        $recurrences = $this->paginate($this->Recurrences);

        $this->set(compact('recurrences'));
    }

    /**
     * View method
     *
     * @param string|null $id Recurrence id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $recurrence = $this->Recurrences->get($id, [
            'contain' => ['Tasks'],
        ]);

        $this->set(compact('recurrence'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $recurrence = $this->Recurrences->newEmptyEntity();
        if ($this->request->is('post')) {
            $recurrence = $this->Recurrences->patchEntity($recurrence, $this->request->getData());
            if ($this->Recurrences->save($recurrence)) {
                $this->Flash->success(__('The recurrence has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The recurrence could not be saved. Please, try again.'));
        }
        $this->set(compact('recurrence'));

    }

    /**
     * Edit method
     *
     * @param string|null $id Recurrence id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $recurrence = $this->Recurrences->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $recurrence = $this->Recurrences->patchEntity($recurrence, $this->request->getData());
            if ($this->Recurrences->save($recurrence)) {
                $this->Flash->success(__('The recurrence has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The recurrence could not be saved. Please, try again.'));
        }
        $this->set(compact('recurrence'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Recurrence id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $recurrence = $this->Recurrences->get($id);
        if ($this->Recurrences->delete($recurrence)) {
            $this->Flash->success(__('The recurrence has been deleted.'));
        } else {
            $this->Flash->error(__('The recurrence could not be deleted. Please, try again.'));
        }

        return $this->redirect(['controller' => 'pages', 'action' => 'display']);
    }
}
