<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Task $task
 * @var \Cake\Collection\CollectionInterface|string[] $users
 * @var \Cake\Collection\CollectionInterface|string[] $departments
 * @var \Cake\Collection\CollectionInterface|string[] $clients
 * @var \Cake\Collection\CollectionInterface|string[] $status
 */
?>

<!--<script src="/jquery.min.js"></script>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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
                <?= $this->element('addTaskForm') ?>

                <?php
                    echo '<div class="input text">';
                    echo '<label>Sub Task</label>';
                    echo '<div id="z_js_wrap_sub_task_item"></div>';
                    echo '</div>';
                ?>

            </fieldset>

            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<style>
    .date{
        margin: auto;
    }
    .sub_task_content {
        margin-left: 10px;
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
