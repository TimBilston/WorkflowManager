<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Task $task
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Task'), ['action' => 'edit', $task->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Task'), ['action' => 'delete', $task->id], ['confirm' => __('Are you sure you want to delete # {0}?', $task->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Task'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Task'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="task view content">
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
                    <th><?= __('Employee') ?></th>
                    <td><?= $task->has('employee') ? $this->Html->link($task->employee->name, ['controller' => 'Employee', 'action' => 'view', $task->employee->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Role Type') ?></th>
                    <td><?= h($task->role_type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($task->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Start Date') ?></th>
                    <td><?= h($task->start_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Due Date') ?></th>
                    <td><?= h($task->due_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Recurring') ?></th>
                    <td><?= $task->recurring ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Task Assignment') ?></h4>
                <?php if (!empty($task->task_assignment)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Manager Id') ?></th>
                            <th><?= __('Task Id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($task->task_assignment as $taskAssignment) : ?>
                        <tr>
                            <td><?= h($taskAssignment->manager_id) ?></td>
                            <td><?= h($taskAssignment->task_id) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'TaskAssignment', 'action' => 'view', $taskAssignment->]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'TaskAssignment', 'action' => 'edit', $taskAssignment->]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'TaskAssignment', 'action' => 'delete', $taskAssignment->], ['confirm' => __('Are you sure you want to delete # {0}?', $taskAssignment->)]) ?>
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
