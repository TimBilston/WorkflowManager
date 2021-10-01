<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Task $task
 * @var \Cake\Collection\CollectionInterface|string[] $users
 * @var \Cake\Collection\CollectionInterface|string[] $departments
 * @var \Cake\Collection\CollectionInterface|string[] $clients
 * @var \Cake\Collection\CollectionInterface|string[] $status

 */
use App\Model\Entity\Task;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Datasource\FactoryLocator;

echo $this->Html->css(['bootstrap' , 'Modal']);
echo $this->Html->script(['jquery-1.4.1.js', 'bootstrap.min' ,'submit.js']);
$tasksTable = \Cake\ORM\TableRegistry::getTableLocator()->get('Tasks');
$task = $tasksTable->find()->where(['Tasks.id' => $taskID]);
$task->contain(['Users']);
$task->contain(['Status']);
$task->contain(['Clients']);
$task = $task->all();
$task = $task->first();
?>

<button type="button" class="viewBtn" data-toggle="modal" data-target="#taskModal<?=$taskID?>">View</button>

<div id= "taskModal<?=$taskID?>" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg" id = "modal<?=$task->id?>">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title"><?=$task->title?></h2>
            </div>
            <div class="modal-body">
                <div class="row">
                    <aside class="column">
                        <div class="side-nav">
                            <h4 class="heading"><?= __('Actions') ?></h4>
                            <button class = "linkButton" onclick = "editForm()">Edit Task</button>
                            <!--replaced with editFORM<?= $this->Html->link(__('Edit Task'), ['action' => 'edit', $task->id], ['class' => 'side-nav-item']) ?>-->
                            <?= $this->Form->postLink(__('Delete Task'), ['action' => 'delete', $task->id], ['confirm' => __('Are you sure you want to delete # {0}?', $task->id), 'class' => 'side-nav-item']) ?>
                        </div>
                    </aside>
                    <div class="column-responsive column-80">
                        <div class="tasks view content"><!-- Echo view content for tasks-->
                            <div class = "task-modal-content">
                                <?= $this->Form->end() ?>
                                <?= $this->Form->create($task, ['url' => ['controller' => 'Tasks','action' => 'edit', $task->id]]); ?>
                                <fieldset>
                                    <div class="desc"><h4>Description</h4></div>
                                        <p class = "displayform"><?= h($task->description) ?></p>
                                        <div class = "editform"><?= $this->Form->control('description' , ['label'=>false])?></div>
                                    <div class ="employee"><h4>Assigned Employee</h4></div>
                                        <p class = "displayform"><?=h($task->user->name)?></p>
                                    <div class ="department"><h4>Department</h4></div>
                                        <p class = "displayform"><?=h($task->department)?></p>
                                        <div class = "editform"><?=$this->Form->control('department_id', ['options' => $departments, 'label'=> false])?></div>
                                    <div class="person"><h4>Client</h4></div>
                                        <p class = "displayform"><?= h(isset($task->client->name))?></p>
                                        <div class = "editform"><?=$this->Form->control('client_id', ['options' => $clients, 'empty' => true, 'label'=>false])?></div>
                                    <div class="status"><h4>Status</h4></div>
                                        <p class = "displayform"><?=h($task->status->name)?></p>
                                        <div class = "editform"><?=$this->Form->control('status_id', ['options' => $status, 'label'=>false])?></div>

                                    <!--Subtasks below-->
                                    <h4><?= __('Related Subtasks') ?></h4>
                                    <!-- <?=$this->element('viewCode',['taskEntity' => $task,])?>-->
                                </fieldset>
                                <div class = "editform"><?=$this->Form->button(__('Submit'))?></div>
                                <?= $this->Form->end() ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let edit = false;
    function editForm(){
        let editFields = document.getElementsByClassName("editform");
        let viewFields = document.getElementsByClassName("displayform")
        if(edit === false){
            for(let i = 0; i < editFields.length; i++){
                editFields[i].style.display = "block";
            }
            for(let i = 0; i < viewFields.length; i++){
                viewFields[i].style.display = "none";
            }
            edit = true;
        }
        else{
            for(let i = 0; i < editFields.length; i++){
                editFields[i].style.display = "none";
            }
            for(let i = 0; i < viewFields.length; i++){
                viewFields[i].style.display = "block";
            }
            edit = false;
        }
    }
</script>
