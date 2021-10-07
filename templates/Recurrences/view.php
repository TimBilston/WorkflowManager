<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Recurrence $recurrence
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
        </div>
    </aside>
    <div class="column-responsive column-custom">
        <div class="recurrences view content">
            <h3><?= h($recurrence->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Recurrence') ?></th>
                    <td><?= h($recurrence->recurrence) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($recurrence->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('No Of Recurrence') ?></th>
                    <td><?= $this->Number->format($recurrence->no_of_recurrence) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Tasks') ?></h4>
                <?php if (!empty($recurrence->tasks)) : ?>
                <div class="table-responsive-custom">
                    <table>
                        <tr>
                            <th class="actions"><?= __('Actions') ?></th>
                            <th><?= __('Title') ?></th>
                            <th><?= __('Description') ?></th>
                            <th><?= __('Start Date') ?></th>
                            <th><?= __('Due Date') ?></th>
                            <th><?= __('Employee Id') ?></th>
                            <th><?= __('Recurrence Type') ?></th>
                            <th><?= __('No Of Recurrence') ?></th>
                            <th><?= __('Department Id') ?></th>
                            <th><?= __('Client Id') ?></th>
                            <th><?= __('Status Id') ?></th>
                        </tr>
                        <?php foreach ($recurrence->tasks as $tasks) : ?>
                        <tr>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Tasks', 'action' => 'view', $tasks->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Tasks', 'action' => 'edit', $tasks->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Tasks', 'action' => 'delete', $tasks->id], ['confirm' => __('Are you sure you want to delete # {0}?', $tasks->id)]) ?>
                            </td>
                            <td><?= h($tasks->title) ?></td>
                            <td><?= h($tasks->description) ?></td>
                            <td><?= h($tasks->start_date) ?></td>
                            <td><?= h($tasks->due_date) ?></td>
                            <td><?= h($tasks->employee_id) ?></td>
                            <td><?= h($tasks->recurrence_type) ?></td>
                            <td><?= h($tasks->no_of_recurrence) ?></td>
                            <td><?= h($tasks->department_id) ?></td>
                            <td><?= h($tasks->client_id) ?></td>
                            <td><?= h($tasks->status_id) ?></td>
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
    .table-responsive-custom{
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .column-custom{
        flex:0 0 100%;
        max-width: 80%;
    }
</style>
