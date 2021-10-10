<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \Cake\Collection\CollectionInterface|string[] $departments
 */
?>

<!--<script src="/js/jquery.min.js"></script>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="users form content">
            <?= $this->Form->create($user) ?>
            <fieldset>
                <legend><?= __('Add User') ?></legend>
                <?php
                    echo $this->Form->control('password');
                    echo $this->Form->control('name');
                    echo $this->Form->control('last_name');
                    echo $this->Form->control('phone');
                    echo $this->Form->control('email');
                    echo $this->Form->control('department_id', ['options' => $departments]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit'), ['id' => 'btn_submit']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<script>
$(function () {
    $('#btn_submit').click(function () {
        var name = $('input[name="name"]').val();
        var nameReg = /^[a-zA-Z]{1,20}$/;
        if (!nameReg.test(name)) {
            alert('name is invalid');
            return false;
        }
        var last_name = $('input[name="last_name"]').val();
        if (!nameReg.test(last_name)) {
            alert('last name is invalid');
            return false;
        }
    });
});




</script>



