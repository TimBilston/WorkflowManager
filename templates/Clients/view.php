<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Client $client
 */

use Cake\ORM\TableRegistry;
use Cake\ORM\Locator\LocatorAwareTrait;
$currentDate = date('d/m/y');
?>
<style>
    .collapsible {
        background-color: #777;!important;
        color: white;
        cursor: pointer;
        width: 100%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
    }

    .active, .collapsible:hover {
        background-color: #b80c3c;
    }

    .test {
        padding: 0 18px;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.2s ease-out;
        background-color: #f1f1f1;
    }
    .collapsible:after {
        content: '\02795'; /* Unicode character for "plus" sign (+) */
        font-size: 13px;
        color: white;
        float: right;
        margin-left: 5px;
    }

    .active:after {
        content: "\2796"; /* Unicode character for "minus" sign (-) */
    }
</style>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Client'), ['action' => 'edit', $client->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Client'), ['action' => 'delete', $client->id], ['confirm' => __('Are you sure you want to delete # {0}?', $client->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Clients'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Client'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Create Task'), ['controller' => 'tasks', 'action' => 'add' , $client->id], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="clients view content">
            <h3><?= h($client->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($client->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Company Name') ?></th>
                    <td><?= h($client->company_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Phone') ?></th>
                    <td><?= h($client->phone) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($client->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Assigned Employee') ?></th>
                    <td><?= $client->has('user') ? $this->Html->link($client->user->name, ['controller' => 'Users', 'action' => 'view', $client->user->id]) : '' ?></td>
                </tr>
                <!--<tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($client->id) ?></td>
                </tr>
                 -->
            </table>
            <!-- 3 Separate tables for Overdue, Current, and Completed-->
            <h3>Related Tasks</h3>
            <button class="collapsible">Overdue Tasks (<?php
                $query = TableRegistry::getTableLocator()->get('Tasks')->find()->where(['client_id' => $client->id ,'status_id'=>3])->count();
                echo $query;
                ?>)</button>
            <div class="test" style = "active">
                <div class="related">
                    <?php if (!empty($client->tasks)) : ?>
                            <table>
                                <thead>
                                <tr>
                                    <th><?= __('Title') ?></th>
                                    <th><?= __('Description') ?></th>
                                    <th><?= __('Due Date') ?></th>
                                    <th><?= __('Employee') ?></th>
                                    <th><?= __('Department Id') ?></th>
                                    <th class="actions"><?= __('Actions') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($client->tasks as $tasks):?>
                                    <?php
                                    if($tasks->status_id =='3'):
                                    ?>
                                    <tr>
                                        <td><?= h($tasks->title) ?></td>
                                        <td><?= h($tasks->description) ?></td>
                                        <td><?= Date('d/m/y',strtotime($tasks->due_date)) ?></td>
                                        <td><?= $client->has('user') ? $this->Html->link($client->user->name, ['controller' => 'Users', 'action' => 'view', $client->user->id]) : '' ?></td>
                                        <td><?= $client->has('department') ? $this->Html->link($client->tasks->department->name, ['controller' => 'Departments', 'action' => 'view', $client->tasks->department->id]) : '' ?></td>

                                        <td class="actions">
                                            <?= $this->Html->link(__('View'), ['controller' => 'Tasks', 'action' => 'view', $tasks->id]) ?>
                                            <?= $this->Html->link(__('Edit'), ['controller' => 'Tasks', 'action' => 'edit', $tasks->id]) ?>
                                            <?= $this->Form->postLink(__('Delete'), ['controller' => 'Tasks', 'action' => 'delete', $tasks->id], ['confirm' => __('Are you sure you want to delete # {0}?', $tasks->id)]) ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                    <?php endif; ?>
                </div>
        </div>

            <button class="collapsible">Current Tasks (<?php $query = TableRegistry::getTableLocator()->get('Tasks')->find()->where(['client_id' => $client->id, 'status_id' => 1])->count();
                echo $query;
                ?>) </button>
            <div class="test">
                <div class="related">
                    <?php if (!empty($client->tasks)) : ?>

                        <div class="table-responsive">
                            <table>
                                <thead>
                                <tr>
                                    <th><?= __('Title') ?></th>
                                    <th><?= __('Description') ?></th>
                                    <th><?= __('Due Date') ?></th>
                                    <th><?= __('Employee') ?></th>
                                    <th><?= __('Department Id') ?></th>
                                    <th class="actions"><?= __('Actions') ?></th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php foreach ($client->tasks as $tasks): ?>
                                    <?php
                                     //compares current date to see if overdue, and status is ongoing (not completed)
                                    $currentDate = date('d/m/y');
                                    if($tasks->status_id == '1')://if tasks status is current
                                        ?>
                                    <tr>
                                        <td><?= h($tasks->title) ?></td>
                                        <td><?= h($tasks->description) ?></td>
                                        <td><?= Date('d/m/y',strtotime($tasks->due_date)) ?></td>
                                        <td><?= $client->has('user') ? $this->Html->link($client->user->name, ['controller' => 'Users', 'action' => 'view', $client->user->id]) : '' ?></td>
                                        <td><?= $client->has('department') ? $this->Html->link($client->tasks->department->name, ['controller' => 'Departments', 'action' => 'view', $client->tasks->department->id]) : '' ?></td>

                                        <td class="actions">
                                            <?= $this->Html->link(__('View'), ['controller' => 'Tasks', 'action' => 'view', $tasks->id]) ?>
                                            <?= $this->Html->link(__('Edit'), ['controller' => 'Tasks', 'action' => 'edit', $tasks->id]) ?>
                                            <?= $this->Form->postLink(__('Delete'), ['controller' => 'Tasks', 'action' => 'delete', $tasks->id], ['confirm' => __('Are you sure you want to delete # {0}?', $tasks->id)]) ?>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <button class="collapsible">Completed Tasks (<?php $query = TableRegistry::getTableLocator()->get('Tasks')->find()->where(['client_id' => $client->id, 'status_id' => 2])->count();
                echo $query; ?>) </button>
            <div class="test">
                <div class="related">
                    <?php if (!empty($client->tasks)) : ?>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                <tr>
                                    <th><?= __('Title') ?></th>
                                    <th><?= __('Description') ?></th>
                                    <th><?= __('Due Date') ?></th>
                                    <th><?= __('Employee') ?></th>
                                    <th><?= __('Department Id') ?></th>
                                    <th class="actions"><?= __('Actions') ?></th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php foreach ($client->tasks as $tasks): ?>
                                    <?php
                                    if($tasks->status_id == '2'):
                                  ?>
                                    <tr>
                                        <td><?= h($tasks->title) ?></td>
                                        <td><?= h($tasks->description) ?></td>
                                        <td><?= Date('d/m/y',strtotime($tasks->due_date)) ?></td>
                                        <td><?= $client->has('user') ? $this->Html->link($client->user->name, ['controller' => 'Users', 'action' => 'view', $client->user->id]) : '' ?></td>
                                        <td><?= $client->has('department') ? $this->Html->link($client->tasks->department->name, ['controller' => 'Departments', 'action' => 'view', $client->tasks->department->id]) : '' ?></td>

                                        <td class="actions">
                                            <?= $this->Html->link(__('View'), ['controller' => 'Tasks', 'action' => 'view', $tasks->id]) ?>
                                            <?= $this->Html->link(__('Edit'), ['controller' => 'Tasks', 'action' => 'edit', $tasks->id]) ?>
                                            <?= $this->Form->postLink(__('Delete'), ['controller' => 'Tasks', 'action' => 'delete', $tasks->id], ['confirm' => __('Are you sure you want to delete # {0}?', $tasks->id)]) ?>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?= $this->element('addTask' , ['clientID' => $client->id])?>
        </div>
    </div>
</div>
<script>
    var coll = document.getElementsByClassName("collapsible");
    var i;

    for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var content = this.nextElementSibling;
            if (content.style.maxHeight){
                content.style.maxHeight = null;
            } else {
                content.style.maxHeight = content.scrollHeight + "px";
            }
        });
    }
</script>
