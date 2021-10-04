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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $recurrence->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $recurrence->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Recurrences'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="recurrences form content">
            <?= $this->Form->create($recurrence) ?>
            <fieldset>
                <legend><?= __('Edit Recurrence') ?></legend>
                <?php
                    echo $this->Form->control('recurrence');
                    echo $this->Form->control('no_of_recurrence');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
