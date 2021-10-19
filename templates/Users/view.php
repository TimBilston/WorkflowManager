<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */

$this->loadHelper('Authentication.Identity');
if ($this->Identity->isLoggedIn()) {
    $currentUserId = $this->Identity->get('id');
}
?>

<div class="row" style="padding-top: 10%">
    <aside class="column">
        <div class="side-nav" style="padding-top: 35%">
            <h4 class="heading"><?= __('Options') ?></h4>
            <?php if ($currentUserId == $user->id){
                echo $this->Html->link(__('Edit My Details'), ['action' => 'edit', $user->id], ['class' => 'side-nav-item']);
            } ?>

            <?= $this->Html->link(__('View Employees'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Create Task'), ['controller' => 'tasks', 'action' => 'add', '?' => ['userId' => $user->id]], ['class' => 'side-nav-item']) ?>

        </div>
    </aside>
    <div class="column-responsive column-80">
        <?php if ($currentUserId == $user->id): ?>
            <h3><?= '<b>My Profile: </b>'.h($user->name) ?></h3>
        <?php else: ?>
            <h3><?= '<b>Current Profile: </b>'.h($user->name) ?></h3>
        <?php endif; ?>

        <div class="users view content" style="padding: 3%">
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($user->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Last Name') ?></th>
                    <td><?= h($user->last_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Phone') ?></th>
                    <td><?= h($user->phone) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($user->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Department') ?></th>
                    <td><?= $user->has('department') ? $this->Html->link($user->department->name, ['controller' => 'Departments', 'action' => 'view', $user->department->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($user->id) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<style>
    .button-24 {
        background:#b80c3c;
        border: 1px solid #b80c3c;
        border-radius: 6px;
        box-shadow: rgba(0, 0, 0, 0.1) 1px 2px 4px;
        box-sizing: border-box;
        color: white !important;
        cursor: pointer;
        display: inline-block;
        font-family: "Lato", sans-serif;
        font-size: 16px;
        line-height: 16px;
        min-height: 40px;
        outline: 0;
        padding: 12px 14px;
        text-align: center;
        text-rendering: geometricprecision;
        text-transform: none;
        user-select: none;
        -webkit-user-select: none;
        touch-action: manipulation;
        vertical-align: middle;
    }

    .button-24:hover,
    .button-24:active {
        background-color: initial;
        background-position: 0 0;
        color: black !important;
    }

    .button-24:active {
        opacity: .5;
    }
</style>
