<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Task Entity
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property \Cake\I18n\FrozenDate $start_date
 * @property \Cake\I18n\FrozenDate $due_date
 * @property int $employee_id
 * @property string $recurrence_type
 * @property int $no_of_recurrence
 * @property int $department_id
 * @property int|null $client_id
 * @property int $status_id
 * @property int|null $recurrence_id
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Department $department
 * @property \App\Model\Entity\Client $client
 * @property \App\Model\Entity\Status $status
 * @property \App\Model\Entity\Recurrence $recurrence
 * @property \App\Model\Entity\Subtask[] $subtasks
 */
class Task extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'title' => true,
        'description' => true,
        'start_date' => true,
        'due_date' => true,
        'employee_id' => true,
        'recurrence_type' => true,
        'no_of_recurrence' => true,
        'department_id' => true,
        'client_id' => true,
        'status_id' => true,
        'recurrence_id' => true,
        'user' => true,
        'department' => true,
        'client' => true,
        'status' => true,
        'recurrence' => true,
        'subtasks' => true,
        'complete_date'=> true,
    ];
}
