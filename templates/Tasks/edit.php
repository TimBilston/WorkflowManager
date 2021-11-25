<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Task $task
 * @var string[]|\Cake\Collection\CollectionInterface $users
 * @var string[]|\Cake\Collection\CollectionInterface $departments
 * @var string[]|\Cake\Collection\CollectionInterface $clients
 * @var string[]|\Cake\Collection\CollectionInterface $status
 * @var string[]|\Cake\Collection\CollectionInterface $recurrences
 */
?>
<style>
    .sub_task_content {
        margin-left: 10px;
    }
</style>
<!-- <script src="/jquery.min.js"></script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $task->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $task->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Tasks'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="tasks form content">
            <?= $this->Form->create($task) ?>
            <div class="text-center">
            <fieldset>
                <legend><?= __('Edit Task') ?></legend>
                <?php

                    echo $this->Form->control('title', ['error' => false, 'style' => 'width: 35%']);
                    echo $this->Form->error('title', ['class' => 'error-message']);

                    echo $this->Form->control('description', ['error' => false, 'type' => 'textarea', 'style' => 'width: 40%']);
                    echo $this->Form->error('description', ['class' => 'error-message']);


                    echo '<div class="row1">';
                        echo $this->Form->control('start_date', ['error' => false]);

                        echo $this->Form->control('due_date', ['error' => false]);
                        echo $this->Form->error('due_date', ['class' => 'error-message']);

                    echo '</div>';

                    echo '<div class="row1">';
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

                        echo $this->Form->control('no_of_recurrence', ['default' => 0, 'error' => false]);
                        echo $this->Form->error('no_of_recurrence', ['wrap' => 'label', null, 'class' => 'error-message']);

                    echo '</div>';

                    echo '<div class="row1">';
                        echo $this->Form->control('client_id', ['options' => $clients, 'empty' => true]);
                        echo $this->Form->control('status_id', ['options' => $status]);
                    echo '</div>';
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<style>
    .error-message {
        color: red;
    }

    .row1 {
        display: inline-table;
        align-content: center;
        margin: 5px;
        text-align: left;
    }

</style>

<script>
    $(function () {
        $('#add_sub_task').click(function () {
            var title = $('input[name="title"]').val();
            window.open('/subtasks/add?title=' + title, 'sub_task_add');
        });
    });
</script>
