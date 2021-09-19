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
$task = $task->all();
$task = $task->first();


?>

<button type="button" class="viewBtn" data-toggle="modal" data-target="#taskModal<?=$taskID?>">View</button>

<div id= "taskModal<?=$taskID?>" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title"><?=$task->title?></h2>
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
                        <div class="tasks view content"><!-- Echo view content for tasks-->
                            <div class = "task-modal-content">
                                <div class="desc"><h4>Description</h4></div>
                                    <p><?= h($task->description) ?></p>
                                <div class ="employee"><h4>Assigned Employee</h4></div>
                                    <p><?=h($task->user->name)?></p>
                                <div class ="department"><h4>Department</h4></div>
                                    <p><?=h($task->department)?></p>
                                <div class="person"><h4>Client</h4></div>
                                    <p><?= h($task->client->name)?></p>
                                <div class="status"><h4>Status</h4></div>
                                    <p><?=h($task->status->name)?></p>
                                <!--Subtasks below-->
                                <h4><?= __('Related Subtasks') ?></h4>
                                <?php if (!empty($task->subtasks)) : ?>
                                    <div class="table-responsive">
                                        <table>
                                            <tr>
                                                <th><?= __('Id') ?></th>
                                                <th><?= __('Description') ?></th>
                                                <th><?= __('Title') ?></th>
                                                <th><?= __('Start Date') ?></th>
                                                <th><?= __('Due Date') ?></th>
                                                <th><?= __('Task Id') ?></th>
                                                <th><?= __('Status Id') ?></th>
                                                <th class="actions"><?= __('Actions') ?></th>
                                            </tr>
                                            <?php foreach ($task->subtasks as $subtasks) : ?>
                                                <tr>
                                                    <td><?= h($subtasks->id) ?></td>
                                                    <td><?= h($subtasks->description) ?></td>
                                                    <td><?= h($subtasks->title) ?></td>
                                                    <td><?= h($subtasks->start_date) ?></td>
                                                    <td><?= h($subtasks->due_date) ?></td>
                                                    <td><?= h($subtasks->task_id) ?></td>
                                                    <td><?= h($subtasks->status_id) ?></td>
                                                    <td class="actions">
                                                        <?= $this->Html->link(__('View'), ['controller' => 'Subtasks', 'action' => 'view', $subtasks->id]) ?>
                                                        <?= $this->Html->link(__('Edit'), ['controller' => 'Subtasks', 'action' => 'edit', $subtasks->id]) ?>
                                                        <?= $this->Form->postLink(__('Delete'), ['controller' => 'Subtasks', 'action' => 'delete', $subtasks->id], ['confirm' => __('Are you sure you want to delete # {0}?', $subtasks->id)]) ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </table>
                                    </div>
                                <?php endif; ?>
                                <!-- <?=$this->element('viewCode',['taskEntity' => $task,])?>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
