<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Subtask $subtask
 * @var \Cake\Collection\CollectionInterface|string[] $tasks
 * @var \Cake\Collection\CollectionInterface|string[] $status
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Subtasks'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="subtasks form content">
            <?= $this->Form->create($subtask) ?>
            <fieldset>
                <legend><?= __('Add Subtask') ?></legend>
                <?php
                    echo $this->Form->control('description', ['type' => 'textarea']);
                    echo $this->Form->control('title');
                    echo $this->Form->control('start_date');
                    echo $this->Form->control('due_date');
                    echo $this->Form->control('task_id', ['options' => $tasks]);
                    echo $this->Form->hidden('status_id', ['value' => 3]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
