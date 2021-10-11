<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Client[]|\Cake\Collection\CollectionInterface $clients
 */

$this->loadHelper('Authentication.Identity');

?>
<div style="padding-top: 10%">
    <link rel="stylesheet" href="webroot/css/buttons.css">
    <h1 class="clients_title"><?= __('Full List of Clients') ?></h1>
    <div class="clients index content" style="padding: 3%">
        <?= $this->Html->link(__('New Client'), ['action' => 'add'], ['class' => 'button new_client']) ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('name') ?></th>
                        <th><?= $this->Paginator->sort('company_name') ?></th>
                        <th><?= $this->Paginator->sort('phone') ?></th>
                        <th><?= $this->Paginator->sort('email') ?></th>
                        <th><?= $this->Paginator->sort('employee_id') ?></th>
                        <th class="actions"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= h($client->name) ?></td>
                        <td><?= h($client->company_name) ?></td>
                        <td><?= h($client->phone) ?></td>
                        <td><?= h($client->email) ?></td>
                        <td><?= $client->has('user') ? $this->Html->link($client->user->name, ['controller' => 'Users', 'action' => 'view', $client->user->id]) : '' ?></td>
                        <td class="actions">
                            <?= $this->Html->link(__('View'), ['action' => 'view', $client->id]) ?>

                            <?php if ($this->Identity->get('department_id') == 2){
                                echo $this->Html->link(__('Edit'), ['action' => 'edit', $client->id]);
                                echo $this->Form->postLink(__('Delete'), ['action' => 'delete', $client->id], ['confirm' => __('Are you sure you want to delete # {0}?', $client->id)]);
                            }?>

                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
        <!--<div class="paginator">
            <ul class="pagination">
                <?= $this->Paginator->first('<< ' . __('first')) ?>
                <?= $this->Paginator->prev('< ' . __('previous')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(__('next') . ' >') ?>
                <?= $this->Paginator->last(__('last') . ' >>') ?>
            </ul>
            <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
        </div>-->
    </div>
</div>
