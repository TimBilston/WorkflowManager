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
            <?= $this->Html->link(__('Edit Manager'), ['action' => 'edit', $manager->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Manager'), ['action' => 'delete', $manager->id], ['confirm' => __('Are you sure you want to delete # {0}?', $manager->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Manager'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Manager'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="manager view content">
            <h3><?= h($manager->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Password') ?></th>
                    <td><?= h($manager->password) ?></td>
                </tr>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($manager->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Last Name') ?></th>
                    <td><?= h($manager->last_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($manager->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Phone') ?></th>
                    <td><?= h($manager->phone) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($manager->id) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Task Assignment') ?></h4>
                <?php if (!empty($manager->task_assignment)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Manager Id') ?></th>
                            <th><?= __('Task Id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($manager->task_assignment as $taskAssignment) : ?>
                        <tr>
                            <td><?= h($taskAssignment->manager_id) ?></td>
                            <td><?= h($taskAssignment->task_id) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'TaskAssignment', 'action' => 'view', $taskAssignment->]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'TaskAssignment', 'action' => 'edit', $taskAssignment->]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'TaskAssignment', 'action' => 'delete', $taskAssignment->], ['confirm' => __('Are you sure you want to delete # {0}?', $taskAssignment->)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
