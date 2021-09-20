<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Task $task
 * @var string[]|\Cake\Collection\CollectionInterface $users
 * @var string[]|\Cake\Collection\CollectionInterface $departments
 * @var string[]|\Cake\Collection\CollectionInterface $clients
 * @var string[]|\Cake\Collection\CollectionInterface $status
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
            <fieldset>
                <legend><?= __('Edit Task') ?></legend>
                <?php
                    echo $this->Form->control('title');
                    echo $this->Form->control('description');
                    echo $this->Form->control('start_date');
                    echo $this->Form->control('due_date');
                    echo $this->Form->control('employee_id', ['options' => $users]);
                    echo $this->Form->control('recurrence');
                    echo $this->Form->control('no_of_recurrence');
                    echo $this->Form->control('department_id', ['options' => $departments]);
                    echo $this->Form->control('client_id', ['options' => $clients, 'empty' => true]);
                    echo $this->Form->control('status_id', ['options' => $status]);

                    echo '<div class="input text">';
                    echo '<label>Sub Task</label>';
                    echo '<div id="z_js_wrap_sub_task_item">';

                    foreach ($subTasks as $k => $subTask) {
                        $isCompleteChecked = $subTask->is_complete ? ' checked' : '';
                        $isCompleteAdminChecked = $subTask->is_complete_admin ? ' checked' : '';
                        echo '<div class="z_js_sub_task_item">';
                        echo '<input class="z_js_sub_task_id" value="' . $subTask->id . '" name="sub_task_id[]" type="hidden" />';
                        echo '<input class="z_js_sub_task_status" value="1" name="sub_task_status_' . $k . '"' . $isCompleteChecked . ' type="checkbox" />';
                        echo '<span class="sub_task_content">' . $subTask->description . '</span>';
                        echo '<input class="z_js_sub_task_content" name="sub_task_content[]" value="' . $subTask->description . '" type="hidden" />';
                        echo '<input class="z_js_sub_task_status_admin" value="1" name="sub_task_status_admin_' . $k . '"' . $isCompleteAdminChecked . ' type="checkbox" style="display: none" />';
                        echo '</div>';
                    }

                    echo '</div>';
                    echo '</div>';
                    echo $this->Form->button(__('Edit SubTask'), ['type' => 'button', 'id' => 'add_sub_task']);

                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
