<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Client Entity
 *
 * @property int $id
 * @property string $name
 * @property string $company_name
 * @property string $phone
 * @property string $email
 * @property int $employee_id
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Task[] $tasks
 */
class Client extends Entity
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
        'name' => true,
        'company_name' => true,
        'phone' => true,
        'email' => true,
        'employee_id' => true,
        'user' => true,
        'tasks' => true,
    ];
}
