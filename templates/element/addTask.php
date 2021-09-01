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
use Cake\ORM\TableRegistry;
use Cake\ORM\Locator\LocatorAwareTrait;

?>
<html lang="en">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="../../webroot/css/home.css">
<link rel="stylesheet" href="../../webroot/css/custom.css">
<link rel="stylesheet" href="../../webroot/css/bootstrap.css">

<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#taskModal">Add related task</button>

<div id= "taskModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Add Task</h3>
            </div>
            <div class="modal-body">
               <!-- GETS THE ADD TASK TEMPLATE ELEMENT templates/element/addTask -->
                <div class="column-responsive column-80">
                    <div class="tasks form content">
                        <div
                        <?=
                        $tasksTable = TableRegistry::getTableLocator()->get('Tasks');
                        $task = $tasksTable->newEmptyEntity();
                        ?>
                        <?= $this->Form->create($task) ?>

                        <fieldset style="width : 60px">
                            <?php
                            echo $this->Form->control('title');
                            echo $this->Form->control('description', ['type' => 'textarea']);

                            echo '<div class="row">';
                                echo '<div class="date">';
                                    echo $this->Form->control('due_date');
                                echo '</div>';
                            echo '</div>';

                            echo $this->Form->control('employee_id', ['options' => $users]);
                            echo $this->Form->control('recurring');
                            echo $this->Form->control('department_id', ['options' => $departments]);
                            echo $this->Form->control('client_id', ['options' => $clients, 'empty' => true]);
                            //id for 'In Progress' is 1
                            echo $this->Form->hidden('status_id', ['value' => 1]);
                            ?>
                        </fieldset>
                        <?= $this->Form->button(__(''),['controller' ])?>

                        <?= $this->Form->end() ?>

                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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


