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
 * @property bool $recurring
 * @property string $role_type
 *
 * @property \App\Model\Entity\Employee $employee
 * @property \App\Model\Entity\TaskAssignment[] $task_assignment
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
        'recurring' => true,
        'role_type' => true,
        'employee' => true,
        'task_assignment' => true,
    ];
}
