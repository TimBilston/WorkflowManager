<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Task $task
 * @var \Cake\Collection\CollectionInterface|string[] $users
 * @var \Cake\Collection\CollectionInterface|string[] $departments
 * @var \Cake\Collection\CollectionInterface|string[] $clients
 * @var \Cake\Collection\CollectionInterface|string[] $status

 */

use App\Model\Entity\Task;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Datasource\FactoryLocator;
echo $this->Html->css(['bootstrap' , 'Modal']);

?>
<html lang="en">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="../../webroot/css/home.css">
<link rel="stylesheet" href="../../webroot/css/custom.css">
<link rel="stylesheet" href="../../webroot/css/bootstrap.css">

<button type="button" data-toggle="modal" data-target="#taskModal">Add related task</button>

<div id= "taskModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog" style="width:600px">
        <div class="modal-content" >
            <div class="modal-header">
                <h3 class="modal-title">Add Task</h3>
            </div>
            <div class="modal-body">
               <!-- GETS THE ADD TASK TEMPLATE ELEMENT templates/element/addTask -->
                <div class="column-responsive">
                    <div class="tasks form content">
                        <div
                        <?php
                        $tasksTable = \Cake\ORM\TableRegistry::getTableLocator()->get('Tasks');
                        $task = $tasksTable->newEmptyEntity();
                        ?>
                        <?= $this->Form->end() ?>
                        <?= $this->Form->create($task, ['url' => ['controller' => 'Tasks','action' => 'add']]); ?>
                        <fieldset>
                            <?=
                            $this->element('addTaskForm', ['clientID' => 'clientID']) ?>
                        </fieldset>
                        <?= $this->Form->submit(__('Submit')); ?>
                        <?= $this->Form->end() ?>

                    </div>
                <div class="modal-footer">
                    <button type="button" data-toggle="modal" data-target="#taskModal">Close</button>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
<style>
    .date{margin: auto;}
</style>
</html>
<script>
    var myModal = document.getElementById('myModal')
    var myInput = document.getElementById('myInput')

    myModal.addEventListener('shown.bs.modal', function () {
        myInput.focus()
    })
</script>
<!-- Latest jQuery (1.11.1) -->
<script src="../../webroot/js/jquery-1.4.1.js"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="../../webroot/js/bootstrap.min.js"></script>


