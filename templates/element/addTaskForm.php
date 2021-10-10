<?php
echo $this->Form->control('title');
echo $this->Form->control('description', ['type' => 'textarea']);


echo '<div class="row">';
echo '<div class="date">';
echo $this->Form->control('start_date');
echo '</div>';
echo '<div class="date">';
echo $this->Form->control('due_date');
echo '</div>';
echo '</div>';

if ($task->employee_id == null) {
    echo $this->Form->control('employee_id', ['options' => $users]);
} else {
    echo  $this->Form->label('Employee Name: '.$userName->name);
}

echo $this->Form->label('Repeat');
echo $this->Form->select('recurrence_type', [
    'Never' => 'Never',
    'Weekly' => 'Weekly',
    'Fortnightly' => 'Fortnightly',
    'Monthly' => 'Monthly',
    'Quarterly' => 'Quarterly',
    'Annually' => 'Annually'
]);

echo $this->Form->control('no_of_recurrence', ['default' => 0]);


if ($task->client_id == null){
    echo $this->Form->control('client_id', ['options' => $clients, 'empty' => 'No Client']);
} else {
    echo $this->Form->label('Client Name: '.$clientName->name);
}


//id for 'In Progress' is 1
echo $this->Form->hidden('status_id', ['value' => 1]);

echo $this->Form->button(__('Add SubTask'), ['type' => 'button', 'id' => 'add_sub_task']);
