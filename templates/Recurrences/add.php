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
            <?= $this->Html->link(__('List Recurrences'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="recurrences form content">
            <?= $this->Form->create($recurrence) ?>
            <fieldset>
                <legend><?= __('Add Recurrence') ?></legend>
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
