<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Manager[]|\Cake\Collection\CollectionInterface $manager
 */
?>
<div class="manager index content">
    <?= $this->Html->link(__('New Manager'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Manager') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('password') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('last_name') ?></th>
                    <th><?= $this->Paginator->sort('email') ?></th>
                    <th><?= $this->Paginator->sort('phone') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($manager as $manager): ?>
                <tr>
                    <td><?= $this->Number->format($manager->id) ?></td>
                    <td><?= h($manager->password) ?></td>
                    <td><?= h($manager->name) ?></td>
                    <td><?= h($manager->last_name) ?></td>
                    <td><?= h($manager->email) ?></td>
                    <td><?= h($manager->phone) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $manager->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $manager->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $manager->id], ['confirm' => __('Are you sure you want to delete # {0}?', $manager->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
