<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Taskassignment Entity
 *
 * @property int $manager_id
 * @property int $task_id
 *
 * @property \App\Model\Entity\Manager $manager
 * @property \App\Model\Entity\Task $task
 */
class Taskassignment extends Entity
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
        'manager_id' => true,
        'task_id' => true,
        'manager' => true,
        'task' => true,
    ];
}
