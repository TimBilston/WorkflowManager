<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Manager Entity
 *
 * @property int $id
 * @property string $password
 * @property string $name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 *
 * @property \App\Model\Entity\TaskAssignment[] $task_assignment
 */
class Manager extends Entity
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
        'password' => true,
        'name' => true,
        'last_name' => true,
        'email' => true,
        'phone' => true,
        'task_assignment' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password',
    ];
}
