<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Task;
use App\Model\Table\SubtasksTable;
use phpDocumentor\Reflection\Types\Integer;

/**
 * Tasks Controller
 *
 * @property \App\Model\Table\TasksTable $Tasks
 * @method \App\Model\Entity\Task[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TasksController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Departments', 'Clients', 'Status'],
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
            'contain' => ['Users', 'Departments', 'Clients', 'Status', 'Subtasks'],
        ]);

        $this->set(compact('task'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $task = $this->Tasks->newEmptyEntity();
        if ($this->request->is('post')) {
            $task = $this->Tasks->patchEntity($task, $this->request->getData());

            if (!$task->due_date == null){
                $task->due_date = $this->offsetWeekend($task->due_date);
            }

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
                    $this->Flash->error(__('The task could not be saved. Please, try again.'));
                }
                //controllers for subtasks-----end

                $this->Flash->success(__('The task has been saved.'));

                //************************RECURRENCE FUNCTION***************************
                if ($task->recurrence == 'Quarterly') {
                    $newIds = $task->id;
                    $changingDate = $task->due_date;
                    for ($i = 0; $i < $task->no_of_recurrence; $i++) {
                        $newTask = $this->Tasks->newEmptyEntity();
                        $newTask = $this->Tasks->patchEntity($newTask, $this->request->getData());
                        $newTask->id = $newIds + 1;
                        $newIds += 1;

                        $newTask->due_date = $this->offsetWeekend($changingDate->addMonth(3));
                        if ($changingDate->isWeekend()) {
                            $changingDate = $newTask->due_date;
                        } else {
                            $changingDate = $changingDate->addMonth(3);
                        }

                        $this->Tasks->save($newTask);
                    }
                } elseif ($task->recurrence == 'Weekly') {
                    $newIds = $task->id;
                    $changingDate = $task->due_date;
                    for ($i = 0; $i < $task->no_of_recurrence; $i++) {
                        $newTask = $this->Tasks->newEmptyEntity();
                        $newTask = $this->Tasks->patchEntity($newTask, $this->request->getData());
                        $newTask->id = $newIds + 1;
                        $newIds += 1;

                        $newTask->due_date = $changingDate->addDays(7);
                        $changingDate = $newTask->due_date;

                        $this->Tasks->save($newTask);
                    }
                } elseif ($task->recurrence == 'Fortnightly'){
                    $newIds = $task->id;
                    $changingDate = $task->due_date;
                    for ($i = 0; $i < $task->no_of_recurrence; $i++) {
                        $newTask = $this->Tasks->newEmptyEntity();
                        $newTask = $this->Tasks->patchEntity($newTask, $this->request->getData());
                        $newTask->id = $newIds + 1;
                        $newIds += 1;

                        $newTask->due_date = $changingDate->addDays(14);
                        $changingDate = $newTask->due_date;
                        $this->Tasks->save($newTask);
                    }
                } elseif ($task->recurrence == 'Monthly'){
                    $newIds = $task->id;
                    $changingDate = $task->due_date;
                    for ($i = 0; $i < $task->no_of_recurrence; $i++) {
                        $newTask = $this->Tasks->newEmptyEntity();
                        $newTask = $this->Tasks->patchEntity($newTask, $this->request->getData());
                        $newTask->id = $newIds + 1;
                        $newIds += 1;

                        $newTask->due_date = $this->offsetWeekend($changingDate->addMonth(1));
                        if ($changingDate->isWeekend()) {
                            $changingDate = $newTask->due_date;
                        } else {
                            $changingDate = $changingDate->addMonth(1);
                        }

                        $this->Tasks->save($newTask);
                    }
                } elseif ($task->recurrence == 'Annually'){
                    $newIds = $task->id;
                    $changingDate = $task->due_date;
                    for ($i = 0; $i < $task->no_of_recurrence; $i++) {
                        $newTask = $this->Tasks->newEmptyEntity();
                        $newTask = $this->Tasks->patchEntity($newTask, $this->request->getData());
                        $newTask->id = $newIds + 1;
                        $newIds += 1;

                        $newTask->due_date = $this->offsetWeekend($changingDate->addYear(1));
                        if ($changingDate->isWeekend()) {
                            $changingDate = $newTask->due_date;
                        } else {
                            $changingDate = $changingDate->addYear(1);
                        }
                        $this->Tasks->save($newTask);
                    }
                }
                    //************************RECURRENCE FUNCTION***************************

                    return $this->redirect(['controller' => 'pages', 'action' => 'display']);
                }
                $this->Flash->error(__('The task could not be saved. Please, try again.'));
            }
            $users = $this->Tasks->Users->find('list', ['limit' => 200]);
            $departments = $this->Tasks->Departments->find('list', ['limit' => 200]);
            $clients = $this->Tasks->Clients->find('list', ['limit' => 200]);
            $status = $this->Tasks->Status->find('list', ['limit' => 200]);
            $this->set(compact('task', 'users', 'departments', 'clients', 'status'));
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

    public function completeTask($id = null){
        $task = $this->Tasks->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $task->status_id = 2;
            if ($this->Tasks->save($task)) {
                $this->Flash->success('The task has been saved.');

                return $this->redirect(['controller' => 'pages', 'action' => 'display']);
            }
            $this->Flash->error(__('The task could not be saved. Please, try again.'));
        }

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
            if ($this->Tasks->save($task)) {
                //subtasks----start
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

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The task could not be saved. Please, try again.'));
        }
        $users = $this->Tasks->Users->find('list', ['limit' => 200]);
        $departments = $this->Tasks->Departments->find('list', ['limit' => 200]);
        $clients = $this->Tasks->Clients->find('list', ['limit' => 200]);
        $status = $this->Tasks->Status->find('list', ['limit' => 200]);
        $subTasks = $this->Tasks->Subtasks->find('all', ['conditions' => ['task_id' => $id]]);
        $this->set(compact('task', 'users', 'departments', 'clients', 'status', 'subTasks'));
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
        if ($this->Tasks->delete($task)) {
            $this->Flash->success(__('The task has been deleted.'));
        } else {
            $this->Flash->error(__('The task could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
