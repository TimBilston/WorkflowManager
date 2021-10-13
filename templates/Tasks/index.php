<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Task[]|\Cake\Collection\CollectionInterface $tasks
 */
?>

<div class="row">
    <aside class="column">
        <div class="side-nav">
            <?= $this->Html->link(__('Add Task'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
            <h4 class="heading"> <?=__('List Tasks') ?></h4>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="tasks index content">
            <?= $this->Html->link(__('New Task'), ['action' => 'add'], ['class' => 'button float-right']) ?>
            <h3><?= __('Tasks') ?></h3>
            <div class="table-responsive">

                <table>
                    <thead>
                        <tr>
                            <th><?= $this->Paginator->sort('id') ?></th>
                            <th><?= $this->Paginator->sort('title') ?></th>
                            <th><?= $this->Paginator->sort('description') ?></th>
                            <th><?= $this->Paginator->sort('start_date') ?></th>
                            <th><?= $this->Paginator->sort('due_date') ?></th>
                            <th><?= $this->Paginator->sort('employee_id') ?></th>
                            <th><?= $this->Paginator->sort('recurrence_type') ?></th>
                            <th><?= $this->Paginator->sort('no_of_recurrence') ?></th>
                            <th><?= $this->Paginator->sort('department_id') ?></th>
                            <th><?= $this->Paginator->sort('client_id') ?></th>
                            <th><?= $this->Paginator->sort('status_id') ?></th>
                            <th><?= $this->Paginator->sort('recurrence_id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?= $this->Number->format($task->id) ?></td>
                            <td><?= h($task->title) ?></td>
                            <td><?= h($task->description) ?></td>
                            <td><?= h($task->start_date) ?></td>
                            <td><?= h($task->due_date) ?></td>
                            <td><?= $task->has('user') ? $this->Html->link($task->user->name, ['controller' => 'Users', 'action' => 'view', $task->user->id]) : '' ?></td>
                            <td><?= h($task->recurrence_type) ?></td>
                            <td><?= $this->Number->format($task->no_of_recurrence) ?></td>
                            <td><?= $task->has('department') ? $this->Html->link($task->department->name, ['controller' => 'Departments', 'action' => 'view', $task->department->id]) : '' ?></td>
                            <td><?= $task->has('client') ? $this->Html->link($task->client->name, ['controller' => 'Clients', 'action' => 'view', $task->client->id]) : '' ?></td>
                            <td><?= $task->has('status') ? $this->Html->link($task->status->name, ['controller' => 'Status', 'action' => 'view', $task->status->id]) : '' ?></td>
                            <td><?= $task->has('recurrence') ? $this->Html->link($task->recurrence->id, ['controller' => 'Recurrences', 'action' => 'view', $task->recurrence->id]) : '' ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $task->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $task->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $task->id], ['confirm' => __('Are you sure you want to delete # {0}?', $task->id)]) ?>
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
    </div>
</div>
