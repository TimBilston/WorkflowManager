<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Task;
use App\Model\Table\SubtasksTable;
use phpDocumentor\Reflection\Types\Integer;

/**
 * Tasks Controller
 *
 * @property \App\Model\Table\RecurrencesTable $Recurrences
 * @method \App\Model\Entity\Recurrence[]|\Cake\Datasource\ResultSetInterface paginate1($object = null, array $settings = [])
 *
 * @property \App\Model\Table\TasksTable $Tasks
 * @method \App\Model\Entity\Task[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TasksController extends AppController
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
            'contain' => ['Users', 'Departments', 'Clients', 'Status', 'Recurrences'],
        ];
        $tasks = $this->paginate($this->Tasks);

        $this->set(compact('tasks'));
    }

    /**
     * View method
     *
     * @param string|null $id Task id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $task = $this->Tasks->get($id, [
            'contain' => ['Users', 'Departments', 'Clients', 'Status', 'Recurrences', 'Subtasks'],
        ]);

        $this->set(compact('task'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add($clientId = null)
    {
        $task = $this->Tasks->newEmptyEntity();

        //Client Name from clientId
        if ($clientId != null){
            $task->client_id = $clientId;
            $clientName = $this->getTableLocator()->get('Clients')->get($clientId);
            $this->set(compact('clientName'));
        }
        //Employee name from params
        if ($this->request->getQueryParams('userId') != null){
            $task->employee_id = $this->request->getQueryParams('userId');
            $userName = $this->getTableLocator()->get('Users')->get($task->employee_id);
            $this->set(compact('userName'));
        }


        if ($this->request->is('post')) {
            $task = $this->Tasks->patchEntity($task, $this->request->getData());

            $task->department_id = $this->getTableLocator()->get('Users')->get($task->employee_id)->department_id;

            if (!$task->due_date == null){
                $task->due_date = $this->offsetWeekend($task->due_date);
            }


            $recurrenceTable = $this->getTableLocator()->get('Recurrences');
            $recurrence = $recurrenceTable->newEmptyEntity();
            $recurrence->recurrence = $task->recurrence_type;
            if ($task->no_of_recurrence != null){
                $recurrence->no_of_recurrence = $task->no_of_recurrence;
            }
            if ($recurrenceTable->save($recurrence)) {
                // The foreign key value was set automatically.
                //echo $task->id;
            }
            $task->recurrence_id = $recurrence->id;


            if ($this->Tasks->save($task)) {
                //controllers for subtasks-----start
                $subTaskDatas = [];
                $sub_task_contents = $this->request->getData('sub_task_content', []);
                foreach ($sub_task_contents as $k => $v) {
                    $subTaskData = [];
                    $subTaskData['description'] = $v;
                    $subTaskData['task_id'] = $task->id;
                    $subTaskData['is_complete'] = $this->request->getData('sub_task_status_' . $k, 0);
                    $subTaskData['is_complete_admin'] = $this->request->getData('sub_task_status_admin_' . $k, 0);
                    $subTaskDatas[] = $subTaskData;
                }

                $subTaskTable = new SubtasksTable();
                $subTaskEntities = $subTaskTable->newEntities($subTaskDatas);
                if (!$subTaskTable->saveMany($subTaskEntities)) {
                    $this->Flash->error(__('The subtask could contain figures. Please, try again.'));
                }
                //controllers for subtasks-----end

                //************************RECURRENCE FUNCTION***************************
                $this->createRecurringTasks($task);
                //************************RECURRENCE FUNCTION***************************

                $this->Flash->success(__('The task has been saved.'));

                return $this->redirect(['controller' => 'pages', 'action' => 'display']);
            }
            $this->Flash->error(__('The task could not be saved. Please, try again.'));
        }

        $users = $this->Tasks->Users->find('list', ['limit' => 200]);
        $departments = $this->Tasks->Departments->find('list', ['limit' => 200]);
        $clients = $this->Tasks->Clients->find('list', ['limit' => 200]);
        $status = $this->Tasks->Status->find('list', ['limit' => 200]);
        $recurrences = $this->Tasks->Recurrences->find('list', ['limit' => 200]);
        $this->set(compact('task', 'users', 'departments', 'clients', 'status', 'recurrences'));
    }

    private function createRecurringTasks($task){
        $newIds = $task->id;
        $changingDate = $task->due_date;
        for ($i = 0; $i < $task->no_of_recurrence; $i++) {
            $newTask = $this->Tasks->newEmptyEntity();
            $newTask = $this->Tasks->patchEntity($newTask, $this->request->getData());
            $newTask->department_id = $this->getTableLocator()->get('Users')->get($newTask->employee_id)->department_id;

            $newTask->recurrence_id = $task->recurrence_id;
            $newIds += 1;
            $newTask->id = $newIds;


            if ($task->recurrence_type == 'Quarterly') {
                $newTask->due_date = $this->offsetWeekend($changingDate->addMonth(3));
                if ($changingDate->isWeekend()) {
                    $changingDate = $newTask->due_date;
                } else {
                    $changingDate = $changingDate->addMonth(3);
                }
            } elseif ($task->recurrence_type == 'Weekly') {
                $newTask->due_date = $changingDate->addDays(7);
                $changingDate = $newTask->due_date;
            } elseif ($task->recurrence_type == 'Fortnightly'){
                $newTask->due_date = $changingDate->addDays(14);
                $changingDate = $newTask->due_date;
            } elseif ($task->recurrence_type == 'Monthly'){
                $newTask->due_date = $this->offsetWeekend($changingDate->addMonth(1));
                if ($changingDate->isWeekend()) {
                    $changingDate = $newTask->due_date;
                } else {
                    $changingDate = $changingDate->addMonth(1);
                }
            } elseif ($task->recurrence_type == 'Annually'){
                $newTask->due_date = $this->offsetWeekend($changingDate->addYear(1));
                if ($changingDate->isWeekend()) {
                    $changingDate = $newTask->due_date;
                } else {
                    $changingDate = $changingDate->addYear(1);
                }
            }

            $this->Tasks->save($newTask);
        }

    }

    private function offsetWeekend($date){
        //Check to see if it is equal to Sat or Sun.
        if ($date->isSaturday()){
            return $date->addDays(2);
        } elseif ($date->isSunday()){
            return $date->addDay(1);
        }
        return $date;
    }

    /**
     * Edit method
     *
     * @param string|null $id Task id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $task = $this->Tasks->get($id, [
            'contain' => [],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $task = $this->Tasks->patchEntity($task, $this->request->getData());

            if (!$task->due_date == null){
                $task->due_date = $this->offsetWeekend($task->due_date);
            }

            $recurrenceTable = $this->getTableLocator()->get('Recurrences');
            $recurrence = $recurrenceTable->get($task->recurrence_id);
            if ($task->recurrence_type != $recurrence->recurrence || $task->no_of_recurrence != $recurrence->no_of_recurrence){

                $recurrence->recurrence = $task->recurrence_type;
                $recurrence->no_of_recurrence = $task->no_of_recurrence;

                $query = $this->Tasks->find('all')
                    ->where(['Tasks.recurrence_id =' => $task->recurrence_id]);
                $results = $query->all();
                $tasksGroup = $results->toList();

                foreach ($tasksGroup as $value){
                    // Status_id 2 = Completed
                    if ($value->due_date > $task->due_date && $value->status_id != 2){
                        $this->Tasks->delete($value);
                    }
                }

                if ($recurrenceTable->save($recurrence)) {
                    // The foreign key value was set automatically.
                    //echo $task->id;
                }
                $this->createRecurringTasks($task);

            }



            if ($this->Tasks->save($task)) {
                //subtasks controllers for edit func----start
                $sub_task_contents = $this->request->getData('sub_task_content', []);
                $subTaskIds = $this->request->getData('sub_task_id', []);
                foreach ($sub_task_contents as $k => $v) {
                    $subTaskData = [];
                    $subTaskData['description'] = $v;
                    $subTaskData['task_id'] = $task->id;
                    $subTaskData['is_complete'] = $this->request->getData('sub_task_status_' . $k, 0);
                    $subTaskData['is_complete_admin'] = $this->request->getData('sub_task_status_admin_' . $k, 0);

                    $subTaskEntity = $this->Tasks->Subtasks->newEmptyEntity();
                    if (!empty($subTaskIds[$k])) {
                        $subTaskList = $this->Tasks->Subtasks->find('list', ['conditions' => ['id' => $subTaskIds[$k]]]);
                        if ($subTaskList->count() > 0) {
                            unset($subTaskData['task_id']);
                            $subTaskEntity = $this->Tasks->Subtasks->get($subTaskIds[$k], ['contain' => []]);
                        }
                    }
                    $subTaskEntity = $this->Tasks->Subtasks->patchEntity($subTaskEntity, $subTaskData);
                    $this->Tasks->Subtasks->save($subTaskEntity);
                }
                //subtasks---end


                $this->Flash->success(__('The task has been saved.'));

                return $this->redirect($this->referer());
            }
            $this->Flash->error(__('The task could not be saved. Please, try again.'));
        }
        $users = $this->Tasks->Users->find('list', ['limit' => 200]);
        $departments = $this->Tasks->Departments->find('list', ['limit' => 200]);
        $clients = $this->Tasks->Clients->find('list', ['limit' => 200]);
        $status = $this->Tasks->Status->find('list', ['limit' => 200]);
        $recurrences = $this->Tasks->Recurrences->find('list', ['limit' => 200]);
        $this->set(compact('task', 'users', 'departments', 'clients', 'status', 'recurrences'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Task id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $task = $this->Tasks->get($id);

        $query = $this->Tasks->find('all')
            ->where(['Tasks.recurrence_id =' => $task->recurrence_id]);
        $taskRecurrenceId = $task->recurrence_id;

        if ($this->Tasks->delete($task)) {
            $this->Flash->success(__('The task has been deleted.'));


            if ($query->isEmpty()){
                $recurrenceTable = $this->getTableLocator()->get('Recurrences');
                $recurrenceEntity = $recurrenceTable->get($taskRecurrenceId);
                $recurrenceTable->delete($recurrenceEntity);
            }

        } else {
            $this->Flash->error(__('The task could not be deleted. Please, try again.'));
        }

        return $this->redirect(['controller' => 'pages', 'action' => 'display']);
    }

    public function completeTask($id = null){
        $task = $this->Tasks->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $task->status_id = 2;
            $task->complete_date = date("Y-m-d");
            if ($this->Tasks->save($task)) {
                $this->Flash->success('The task has been saved.');

                return $this->redirect(['controller' => 'pages', 'action' => 'display']);
            }
            $this->Flash->error(__('The task could not be saved. Please, try again.'));
        }

    }

    public function getTask()
    {
//        header('Content-type: application/json; charset=utf-8');
        $result = ['code' => 200, 'message' => 'success', 'data' => []];

        $params = $this->request->getQueryParams();
        $begin = $params['begin'];
        $end = $params['end'];
        $user_id = $params['user_id'];

        $begin = date('Y-m-d', strtotime($begin));
        $end = date('Y-m-d', strtotime($end));

        if (empty($begin) || empty($end) || empty($user_id)) {
            $result['code'] = 40011;
            $result['message'] = 'param error';
            exit(json_encode($result));
        }

        $tasks = $this->Tasks->find('all')
            ->contain(['Users', 'Status', 'Clients', 'Subtasks'])
            ->where([
                'Tasks.employee_id' => $user_id,
                'due_date >=' => $begin,
                'due_date <=' => $end,
            ])->all();

        $view = new \App\View\AppView();
        $html = '';
        $modal = '';
        foreach ($tasks as $task) {
            $subTasksCount = 0;
            $completeCount = 0;
            if (!empty($task->subtasks)) {
                $subTasksCount = count($task->subtasks);
                foreach ($task->subtasks as $v) {
                    if ($v->is_complete) {
                        $completeCount++;
                    }
                }
            }

            $clientName = 'No Client';
            if ($task->client !== null) {
                $clientName = 'Client: ' . $task->client->name;
            }

            if ($task->status->name == 'Completed') {
                $bgColor = '#a0da92';
            } else {
                $bgColor = '';
            }

            $html .= '<li class="drag-item" id="' . $task->id . '" style="background-color: ' . $bgColor . '">' .
                '<h1 title=' . $task->title . '>' . $task->title . '</h1>' .
                '<p class="due_time" style="visibility: hidden; display: none">' . $task->due_date . '</p>' .
                '<p class="person">' . $clientName . '</p>' .
                '<p class="employee_id" style="visibility: hidden; display: none">' . $task->user->id . '</p>' .
                '<p class="status" style="visibility: hidden; display: none">' . $task->status->name . '</p>' .
                '<p class="task_process" style="visibility: hidden; display: none">' . $completeCount . '/' . $subTasksCount . '</p>';

            $html .= '<div class="view_wrap"></div>';

            $html .= '<div style="display:inline-block">';


            if ($task->status->name != 'Completed') {
                $html .= $view->Form->postButton(__('Complete'),
                    ['controller' => 'tasks', 'action' => 'completeTask', $task->id], ['class' => 'submit_complete', 'type' => 'button']
                );
            }

            $html .= '</li>';

            $modal .= '<div class = "modals" id="' . $task->id . '" style="display:none">' . $view->element('viewTask', ['taskID' => $task->id]) .  '</div>';
            $html .= '</div>';
        }

        $result['data'] = [
            'tasks' => $tasks,
            'html' => $html,
            'modal' => $modal,
        ];
        exit(json_encode($result));
    }






}
