<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Task $task
 */

$this->Html->css('cake.css')
?>

<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Task'), ['action' => 'edit', $task->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete This Task Only'), ['action' => 'delete', $task->id], ['confirm' => __('Are you sure you want to delete # {0}?', $task->id), 'class' => 'side-nav-item']) ?>

            <?= $this->Form->postLink(__('Delete All Recurring Tasks'), ['controller' => 'recurrences', 'action' => 'delete', $task->recurrence_id],
                ['confirm' => __('Are you sure you want to delete delete all tasks related in this recurrence, even previous dates?', $task->id), 'class' => 'side-nav-item']) ?>

            <?= $this->Html->link(__('List Tasks'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Task'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
            <?= $task->has('recurrence') ? $this->Html->link('Manage Recurrence', ['controller' => 'Recurrences', 'action' => 'view', $task->recurrence->id], ['class' => 'button-24', 'role' => 'button']) : '' ?>

        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="tasks view content">
            <h3><?= h($task->title) ?></h3>
            <table>
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
                    <th><?= __('Recurrence Type') ?></th>
                    <td><?= h($task->recurrence_type) ?></td>
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
                            <th><?= __('Task Id') ?></th>
                            <th><?= __('Status Id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($task->subtasks as $subtasks) : ?>
                        <tr>
                            <td><?= h($subtasks->id) ?></td>
                            <td><?= h($subtasks->description) ?></td>
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
    </div>
</div>

<style>
    .button-24 {
        background:#b80c3c;
        border: 1px solid #b80c3c;
        border-radius: 6px;
        box-shadow: rgba(0, 0, 0, 0.1) 1px 2px 4px;
        box-sizing: border-box;
        color: white !important;
        cursor: pointer;
        display: inline-block;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 16px;
        line-height: 16px;
        min-height: 40px;
        outline: 0;
        padding: 12px 14px;
        text-align: center;
        text-rendering: geometricprecision;
        text-transform: none;
        user-select: none;
        -webkit-user-select: none;
        touch-action: manipulation;
        vertical-align: middle;
    }

    .button-24:hover,
    .button-24:active {
        background-color: initial;
        background-position: 0 0;
        color: black !important;
    }

    .button-24:active {
        opacity: .5;
    }
</style>
