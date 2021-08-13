<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Subtask[]|\Cake\Collection\CollectionInterface $subtasks
 */
?>
<div class="subtasks index content">
    <?= $this->Html->link(__('New Subtask'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Subtasks') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('description') ?></th>
                    <th><?= $this->Paginator->sort('title') ?></th>
                    <th><?= $this->Paginator->sort('start_date') ?></th>
                    <th><?= $this->Paginator->sort('due_date') ?></th>
                    <th><?= $this->Paginator->sort('task_id') ?></th>
                    <th><?= $this->Paginator->sort('status_id') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subtasks as $subtask): ?>
                <tr>
                    <td><?= $this->Number->format($subtask->id) ?></td>
                    <td><?= h($subtask->description) ?></td>
                    <td><?= h($subtask->title) ?></td>
                    <td><?= h($subtask->start_date) ?></td>
                    <td><?= h($subtask->due_date) ?></td>
                    <td><?= $subtask->has('task') ? $this->Html->link($subtask->task->title, ['controller' => 'Tasks', 'action' => 'view', $subtask->task->id]) : '' ?></td>
                    <td><?= $subtask->has('status') ? $this->Html->link($subtask->status->name, ['controller' => 'Status', 'action' => 'view', $subtask->status->id]) : '' ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $subtask->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $subtask->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $subtask->id], ['confirm' => __('Are you sure you want to delete # {0}?', $subtask->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
