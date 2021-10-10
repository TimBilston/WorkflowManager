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
echo $this->Html->script(['jquery-1.4.1.js', 'bootstrap.min']);
$tasksTable = \Cake\ORM\TableRegistry::getTableLocator()->get('Tasks');
$task = $tasksTable->find()->where(['Tasks.id' => $taskID]);
$task->contain(['Users']);
$task->contain(['Status']);
$task->contain(['Clients']);
$task->contain(['Recurrences']);
$task = $task->all();
$task = $task->first();
?>

<button type="button" class="viewBtn" data-toggle="modal" data-target="#taskModal<?=$taskID?>">View</button>

<div id= "taskModal<?=$taskID?>" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg" id = "modal<?=$task->id?>">
        <div class="modal-content">
            <div class="modal-header">
                <div class = "displayform"><h3 class="modal-title"><?=$task->title?></h3></div>
                <div class = "editform"><h2 class="modal-title"><?= $this->Form->control('title',['label'=>false])?></h2></div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <aside class="column">
                        <div class="side-nav">
                            <h4 class="heading"><?= __('Actions') ?></h4>
                            <button class = "linkButton" onclick = "editForm()">Edit Task</button>
                            <!--replaced with editFORM<?= $this->Html->link(__('Edit Task'), ['action' => 'edit', $task->id], ['class' => 'side-nav-item']) ?>-->
                            <?= $this->Form->postLink(__('Delete Task'), ['controller' => 'Tasks', 'action' => 'delete', $task->id], ['confirm' => __('Are you sure you want to delete # {0}?', $task->id), 'class' => 'side-nav-item']) ?>
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
                                    <div class="row" style="padding: 20px">
                                        <div class = "date">
                                        <div class="due_time"><h4>Start Date</h4></div>
                                            <p class = "displayform"><?= h(date('d/m/y',strtotime($task->start_date))) ?></p>
                                            <div class = "editform"><?= $this->Form->control('start_date', ['label'=>false])?></div>
                                        </div>
                                        <div class = "date">
                                        <div class="due_time"><h4>Due Date</h4></div>
                                            <p class = "displayform"><?= h(date('d/m/y',strtotime($task->due_date))) ?></p>
                                            <div class = "editform"><?= $this->Form->control('due_date', ['label'=>false])?></div>
                                        </div>
                                    </div>
                                    <div class ="row" style="margin-inline: auto;">
                                        <div class =""><h4>Recurrence</h4></div>
                                        <div class =""><h4>No of Recurrence</h4></div>
                                    </div>
                                    <div class="row" style="display: flex">
                                        <p class = "displayform" style="padding-left: 56px;"><?=h($task->recurrence_type)?></p>
                                        <div class = "editform" style="padding-left: 56px;"><?=$this->Form->select('recurrence_type', [
                                                'Never' => 'Never',
                                                'Weekly' => 'Weekly',
                                                'Fortnightly' => 'Fortnightly',
                                                'Monthly' => 'Monthly',
                                                'Quarterly' => 'Quarterly',
                                                'Annually' => 'Annually']);?></div>
                                        <p class = "displayform" style="padding-left: 95px;"><?=h($task->no_of_recurrence)?></p>
                                        <div class = "editform"><?=$this->Form->control('no_of_recurrence', ['label'=> false])?></div>
                                    </div>
                                    <div class ="employee"><h4>Assigned Employee</h4></div>
                                        <p class = "displayform"><?=h($task->user->name)?></p>
                                      <div class="person"><h4>Client</h4></div>
                                        <p class = "displayform"><?php if(isset($task->client)){echo h($task->client->name);}else{echo "No Client";}?></p>
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
