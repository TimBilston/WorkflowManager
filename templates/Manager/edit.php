<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Manager $manager
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $manager->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $manager->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Manager'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="manager form content">
            <?= $this->Form->create($manager) ?>
            <fieldset>
                <legend><?= __('Edit Manager') ?></legend>
                <?php
                    echo $this->Form->control('password');
                    echo $this->Form->control('name');
                    echo $this->Form->control('last_name');
                    echo $this->Form->control('email');
                    echo $this->Form->control('phone');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
