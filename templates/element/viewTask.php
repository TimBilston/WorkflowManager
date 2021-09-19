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

echo $this->Html->css(['bootstrap']);
echo $this->Html->script(['jquery-1.4.1.js', 'bootstrap.min']);
$tasksTable = \Cake\ORM\TableRegistry::getTableLocator()->get('Tasks');
$task = $tasksTable->find()->where(['Tasks.id' => $taskID]);
$task->contain(['Users']);
$task->contain(['Status']);
$task->contain(['Clients']);
$task = $task->all();
$task = $task->first();


?>

<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#taskModal<?=$taskID?>">View</button>

<div id= "taskModal<?=$taskID?>" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><?=$task->title?></h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <aside class="column">
                        <div class="side-nav">
                            <h4 class="heading"><?= __('Actions') ?></h4>
                            <?= $this->Html->link(__('Edit Task'), ['action' => 'edit', $task->id], ['class' => 'side-nav-item']) ?>
                            <?= $this->Form->postLink(__('Delete Task'), ['action' => 'delete', $task->id], ['confirm' => __('Are you sure you want to delete # {0}?', $task->id), 'class' => 'side-nav-item']) ?>
                        </div>
                    </aside>
                    <div class="column-responsive column-80">
                        <div class="tasks view content">
                            <h4>Description</h4>
                                <p><?= h($task->description) ?></p>
                            <h4>Assignee</h4>
                                <p><?=h($task->user->name)?></p>
                            <h4>Department</h4>
                                <p><?=h($task->department)?></p>
                            <h4>Client</h4>
                                <p><?= h($task->client->name)?></p>
                            <!-- <?=$this->element('viewCode',['taskEntity' => $task,])?>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
