<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Subtask Entity
 *
 * @property int $id
 * @property string $description
 * @property string $title
 * @property \Cake\I18n\FrozenDate $start_date
 * @property \Cake\I18n\FrozenDate $due_date
 * @property int $task_id
 * @property int $status_id
 *
 * @property \App\Model\Entity\Task $task
 * @property \App\Model\Entity\Status $status
 */
class Subtask extends Entity
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
        'description' => true,
        'title' => true,
        'start_date' => true,
        'due_date' => true,
        'task_id' => true,
        'status_id' => true,
        'task' => true,
        'status' => true,
    ];
}
