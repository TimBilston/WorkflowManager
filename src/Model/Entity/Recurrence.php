<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Recurrence Entity
 *
 * @property int $id
 * @property string $recurrence
 * @property int $no_of_recurrence
 *
 * @property \App\Model\Entity\Task[] $tasks
 */
class Recurrence extends Entity
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
        'recurrence' => true,
        'no_of_recurrence' => true,
        'tasks' => true,
    ];
}
