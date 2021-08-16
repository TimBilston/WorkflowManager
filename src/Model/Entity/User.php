<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $password
 * @property string $name
 * @property string $last_name
 * @property string $phone
 * @property string $email
 * @property \Cake\I18n\FrozenDate $created
 * @property int $department_id
 *
 * @property \App\Model\Entity\Department $department
 */
class User extends Entity
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
        'phone' => true,
        'email' => true,
        'created' => true,
        'department_id' => true,
        'department' => true,
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
