<table>
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('title') ?></th>
            <th><?= $this->Paginator->sort('description') ?></th>
            <!--<th><?= $this->Paginator->sort('start_date') ?></th>-->
            <th><?= $this->Paginator->sort('due_date') ?></th>
            <th><?= $this->Paginator->sort('client_id') ?></th>
            <th><?= $this->Paginator->sort('employee_id') ?></th>
            <th><?= $this->Paginator->sort('recurrence_type') ?></th>
            <th><?= $this->Paginator->sort('no_of_recurrence') ?></th>
            <th><?= $this->Paginator->sort('department_id') ?></th>
            <th><?= $this->Paginator->sort('status_id') ?></th>
            <!--<th><?= $this->Paginator->sort('recurrence_id') ?></th>-->
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tasks as $task):
            if($task->status_id == $status):?>
            <tr>
                <td><?= $this->Number->format($task->id) ?></td>
                <td><?= h($task->title) ?></td>
                <td><?= h($task->description) ?></td>
                <!--<td><?= h($task->start_date) ?></td>-->
                <td><?= h($task->due_date) ?></td>
                <td><?= $task->has('client') ? $this->Html->link($task->client->name, ['controller' => 'Clients', 'action' => 'view', $task->client->id]) : '' ?></td>
                <td><?= $task->has('user') ? $this->Html->link($task->user->name, ['controller' => 'Users', 'action' => 'view', $task->user->id]) : '' ?></td>
                <td><?= h($task->recurrence_type) ?></td>
                <td><?= $this->Number->format($task->no_of_recurrence) ?></td>
                <td><?= $task->has('department') ? $this->Html->link($task->department->name, ['controller' => 'Departments', 'action' => 'view', $task->department->id]) : '' ?></td>
                <td><?= $task->has('status') ? $this->Html->link($task->status->name, ['controller' => 'Status', 'action' => 'view', $task->status->id]) : '' ?></td>
                <!--<td><?= $task->has('recurrence') ? $this->Html->link($task->recurrence->id, ['controller' => 'Recurrences', 'action' => 'view', $task->recurrence->id]) : '' ?></td>-->
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $task->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $task->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $task->id], ['confirm' => __('Are you sure you want to delete # {0}?', $task->id)]) ?>
                </td>
            </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    </tbody>
</table>
