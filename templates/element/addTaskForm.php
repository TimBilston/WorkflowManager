<?php
echo $this->Form->label('Title*');
echo $this->Form->control('title', ['error' => false, 'label' => false]);
echo $this->Form->error('title', ['class' => 'error-message']);

echo $this->Form->label('Description*');
echo $this->Form->control('description', ['type' => 'textarea', 'error' => false, 'label' => false]);
echo $this->Form->error('description', ['class' => 'error-message']);

echo '<div class="row">';
echo '<div class="date">';
echo $this->Form->label('Start Date*');
echo $this->Form->control('start_date', ['label' => false]);

echo '</div>';
echo '<div class="date">';
echo $this->Form->label('Due Date*');
echo $this->Form->control('due_date', ['error' => false, 'label' => false]);
echo $this->Form->error('due_date', ['class' => 'error-message']);
echo '</div>';
echo '</div>';

echo $this->Form->label('Employee*');
if (!isset($userName)){
    echo $this->Form->control('employee_id', ['options' => $users, 'label' => false]);
} else {
    if ($task->employee_id == null) {
        echo $this->Form->control('employee_id', ['options' => $users, 'label' => false]);
    } else {
        echo  $this->Form->label('Employee Name: '.$userName->name);
    }
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

echo $this->Form->label('Number of Repeats');
echo $this->Form->control('no_of_recurrence', ['default' => 0, 'error' => false, 'class' => 'someClass', 'label' => false]);
echo $this->Form->error('no_of_recurrence', ['wrap' => 'label', null, 'class' => 'error-message']);


if (!isset($clientName)){
    echo $this->Form->control('client_id', ['options' => $clients, 'empty' => 'No Client']);
} else {
    if ($task->client_id == null){
        echo $this->Form->control('client_id', ['options' => $clients, 'empty' => 'No Client']);
    } else {
        echo $this->Form->label('Client Name: '.$clientName->name);
    }
}


//id for 'In Progress' is 1
echo $this->Form->hidden('status_id', ['value' => 1]);

echo $this->Form->button(__('Add SubTask'), ['type' => 'button', 'id' => 'add_sub_task']);
?>
<style>

.error-message {
    color: red;
}
</style>
