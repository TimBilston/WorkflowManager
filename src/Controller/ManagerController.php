<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Manager Controller
 *
 * @property \App\Model\Table\ManagerTable $Manager
 * @method \App\Model\Entity\Manager[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ManagerController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $manager = $this->paginate($this->Manager);

        $this->set(compact('manager'));
    }

    /**
     * View method
     *
     * @param string|null $id Manager id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $manager = $this->Manager->get($id, [
            'contain' => ['TaskAssignment'],
        ]);

        $this->set(compact('manager'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $manager = $this->Manager->newEmptyEntity();
        if ($this->request->is('post')) {
            $manager = $this->Manager->patchEntity($manager, $this->request->getData());
            if ($this->Manager->save($manager)) {
                $this->Flash->success(__('The manager has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The manager could not be saved. Please, try again.'));
        }
        $this->set(compact('manager'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Manager id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $manager = $this->Manager->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $manager = $this->Manager->patchEntity($manager, $this->request->getData());
            if ($this->Manager->save($manager)) {
                $this->Flash->success(__('The manager has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The manager could not be saved. Please, try again.'));
        }
        $this->set(compact('manager'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Manager id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $manager = $this->Manager->get($id);
        if ($this->Manager->delete($manager)) {
            $this->Flash->success(__('The manager has been deleted.'));
        } else {
            $this->Flash->error(__('The manager could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
