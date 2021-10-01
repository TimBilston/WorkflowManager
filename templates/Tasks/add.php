<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Task $task
 * @var \Cake\Collection\CollectionInterface|string[] $users
 * @var \Cake\Collection\CollectionInterface|string[] $departments
 * @var \Cake\Collection\CollectionInterface|string[] $clients
 * @var \Cake\Collection\CollectionInterface|string[] $status
 * @var \Cake\Collection\CollectionInterface|string[] $recurrences
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Tasks'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="tasks form content">
            <?= $this->Form->create($task) ?>
            <fieldset>
                <legend><?= __('Add Task') ?></legend>
                <?php
                echo $this->Form->control('title');
                echo $this->Form->control('description', ['type' => 'textarea']);


                echo '<div class="row">';
                echo '<div class="date">';
                echo $this->Form->control('start_date');
                echo '</div>';
                echo '<div class="date">';
                echo $this->Form->control('due_date');
                echo '</div>';
                echo '</div>';

                echo $this->Form->control('employee_id', ['options' => $users]);

                echo $this->Form->label('Repeat');
                echo $this->Form->select('recurrence_type', [
                    'Never' => 'Never',
                    'Weekly' => 'Weekly',
                    'Fortnightly' => 'Fortnightly',
                    'Monthly' => 'Monthly',
                    'Quarterly' => 'Quarterly',
                    'Annually' => 'Annually'
                ]);

                echo $this->Form->control('no_of_recurrence', ['default' => 0]);

                echo $this->Form->control('department_id', ['options' => $departments]);
                echo $this->Form->control('client_id', ['options' => $clients, 'empty' => 'No Client']);

                //id for 'In Progress' is 1
                echo $this->Form->hidden('status_id', ['value' => 1]);

                echo $this->Form->control('employee_id', ['options' => $users]);

                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
