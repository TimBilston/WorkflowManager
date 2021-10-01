<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Recurrence[]|\Cake\Collection\CollectionInterface $recurrences
 */
?>
<div class="recurrences index content">
    <?= $this->Html->link(__('New Recurrence'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Recurrences') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('recurrence') ?></th>
                    <th><?= $this->Paginator->sort('no_of_recurrence') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recurrences as $recurrence): ?>
                <tr>
                    <td><?= $this->Number->format($recurrence->id) ?></td>
                    <td><?= h($recurrence->recurrence) ?></td>
                    <td><?= $this->Number->format($recurrence->no_of_recurrence) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $recurrence->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $recurrence->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $recurrence->id], ['confirm' => __('Are you sure you want to delete # {0}?', $recurrence->id)]) ?>
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
