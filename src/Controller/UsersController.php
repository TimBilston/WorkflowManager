<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\User;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use phpDocumentor\Reflection\Types\This;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // Configure the login action to not require authentication, preventing
        // the infinite redirect loop issue
        $this->Authentication->addUnauthenticatedActions(['login']);
        $this->Authorization->skipAuthorization();
    }


    public function login()
    {

        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();
        // regardless of POST or GET, redirect if user is logged in
        if ($result->isValid()) {
            // redirect to /articles after login success
            $redirect = $this->request->getQuery('redirect', [
                'controller' => 'Pages',
                'action' => 'display',
            ]);

            return $this->redirect($redirect);
        }
        // display error if user submitted and authentication failed
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error(__('Invalid username or password'));
        }
    }

    public function logout()
    {

        $result = $this->Authentication->getResult();
        // regardless of POST or GET, redirect if user is logged in
        if ($result->isValid()) {
            $this->Authentication->logout();
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Departments', 'Tasks' ],
        ];
        $users = $this->paginate($this->Users);

        $departments = $this->Users->Departments->find('list', ['limit' => 200]);
        $clients = $this->Users->Tasks->Clients->find('list', ['limit' => 200]);
        $status = $this->Users->Tasks->Status->find('list', ['limit' => 200]);
        $this->set(compact('users' , 'departments', 'clients', 'status'));
    }


    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Departments'],
        ]);

        $this->set(compact('user'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        $this->Authorization->authorize($user);


        if ($this->request->is('post')) {
            $userData = $this->request->getData();
            $userData['name'] = ucfirst($userData['name']);
            $user = $this->Users->patchEntity($user, $userData);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $departments = $this->Users->Departments->find('list', ['limit' => 200]);
        $this->set(compact('user', 'departments'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        $this->Authorization->authorize($user);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $departments = $this->Users->Departments->find('list', ['limit' => 200]);
        $this->set(compact('user', 'departments'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);

        $this->Authorization->authorize($user);

        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    public function Dates(){
        if ($this->request->is(['patch', 'post', 'get', 'put'])) {
        //process the ajax request here
            echo 'test';
            exit;
        }
    }
    public function changeDates($id = null){//Date is passed via ajax, Tasks are sent back for that specific day
        $this->autoRender = false;

        if ($this->request->is(['patch', 'post', 'get', 'put'])) {
            $currentMonday = strtotime($id);
            $currentMonday = gmdate("Y-m-d",$currentMonday);//converts from UNIX to same format as DB

            //$Users = $this->Users->find()->all();
            $Tasks = $this->Users->Tasks->find('all')->where(['Tasks.due_date' >= $currentMonday ,'Tasks.due_date' <= $currentMonday])->contain('Users');//this finds all tasks with correct date
            foreach ($Tasks as $task){//Initialises every task as an invisible card
                ?>
                    <li class="task-card" id =<?=$task->id?>>
                        <h4 style = "margin-bottom: 0rem"><?=$task->title?></h4>
                        <p class="due_time"><?=date_format($task->due_date, "d/m/y")?></p>
                        <p class ="person"><?=$task->user->id?></p>
                        <p class="desc" ><?=substr($task->description,0,20)?>...</p>
                        <button type="button" class="viewBtn" data-toggle="modal" data-target="#exampleModal" data-id="<?=$task->id?>">View</button>
                    </li>
                <?php
            }
            exit;
        }
    }
    public function getModal($id = null){
        $this->autoRender = false;

        if ($this->request->is(['patch', 'post', 'get', 'put'])) {
            $task = $this->Users->Tasks->find()->where(['Tasks.id' => $id]);
            $task->contain(['Users']);
            $task->contain(['Status']);
            $task->contain(['Clients']);
            $task->contain(['Recurrences']);
            $task = $task->all();
            $task = $task->first();

            echo json_encode($task);
            exit;
        }
    }
}
