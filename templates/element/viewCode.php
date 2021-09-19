<?php
$task = $taskEntity;
?>

<h3><?= h($task->title) ?></h3>
<div>
    <table id = <?=$task->id?>>
        <tr>
            <th><?= __('Title') ?></th>
            <td><?= h($task->title) ?></td>
        </tr>
        <tr>
            <th><?= __('Description') ?></th>
            <td><?= h($task->description) ?></td>
        </tr>
        <tr>
            <th><?= __('User') ?></th>
            <td><?= $task->has('user') ? $this->Html->link($task->user->name, ['controller' => 'Users', 'action' => 'view', $task->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Recurrence') ?></th>
            <td><?= h($task->recurrence) ?></td>
        </tr>
        <tr>
            <th><?= __('Department') ?></th>
            <td><?= $task->has('department') ? $this->Html->link($task->department->name, ['controller' => 'Departments', 'action' => 'view', $task->department->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Client') ?></th>
            <td><?= $task->has('client') ? $this->Html->link($task->client->name, ['controller' => 'Clients', 'action' => 'view', $task->client->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= $task->has('status') ? $this->Html->link($task->status->name, ['controller' => 'Status', 'action' => 'view', $task->status->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($task->id) ?></td>
        </tr>
        <tr>
            <th><?= __('No Of Recurrence') ?></th>
            <td><?= $this->Number->format($task->no_of_recurrence) ?></td>
        </tr>
        <tr>
            <th><?= __('Start Date') ?></th>
            <td><?= h($task->start_date) ?></td>
        </tr>
        <tr>
            <th><?= __('Due Date') ?></th>
            <td><?= h($task->due_date) ?></td>
        </tr>
    </table>
    <div class="related">
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
    </div>
</div>

