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
<!--<script src="/js/jquery.min.js"></script>-->
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
                <h2 class="tasks"><?= __('Add Task') ?></h2>
                <?= $this->element('addTaskForm') ?>

<!--                --><?php
//                    echo '<div class="input text">';
//                    echo '<label>Sub Task</label>';
//                    echo '<div id="z_js_wrap_sub_task_item"></div>';
//                    echo '</div>';
//                ?>
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
    .star {
        color: #606c76;
        margin-bottom: 10px;
        display: inline-block;
        text-indent: 10px;
        font-size: 13px;
    }

</style>

<script>
    $(function () {
        $('#add_sub_task').click(function () {
            var title = $('input[name="title"]').val();
            window.open('/subtasks/add?title=' + title, 'sub_task_add');
        });

    });

 //   $('input[name="title"]').after('<span class="star">*cannot be empty or have any symbols </span>');
 //   $('textarea[name="description"]').after('<span class="star">*cannot be empty or have any symbols </span>');
</script>
