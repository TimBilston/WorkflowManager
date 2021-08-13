<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Subtask $subtask
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Subtask'), ['action' => 'edit', $subtask->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Subtask'), ['action' => 'delete', $subtask->id], ['confirm' => __('Are you sure you want to delete # {0}?', $subtask->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Subtasks'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Subtask'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="subtasks view content">
            <h3><?= h($subtask->title) ?></h3>
            <table>
                <tr>
                    <th><?= __('Description') ?></th>
                    <td><?= h($subtask->description) ?></td>
                </tr>
                <tr>
                    <th><?= __('Title') ?></th>
                    <td><?= h($subtask->title) ?></td>
                </tr>
                <tr>
                    <th><?= __('Task') ?></th>
                    <td><?= $subtask->has('task') ? $this->Html->link($subtask->task->title, ['controller' => 'Tasks', 'action' => 'view', $subtask->task->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Status') ?></th>
                    <td><?= $subtask->has('status') ? $this->Html->link($subtask->status->name, ['controller' => 'Status', 'action' => 'view', $subtask->status->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($subtask->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Start Date') ?></th>
                    <td><?= h($subtask->start_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Due Date') ?></th>
                    <td><?= h($subtask->due_date) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
