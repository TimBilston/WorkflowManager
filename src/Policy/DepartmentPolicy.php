<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Department;
use Authorization\IdentityInterface;

/**
 * Department policy
 */
class DepartmentPolicy
{
    /**
     * Check if $user can add Department
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Department $department
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Department $department)
    {
        return false;
    }

    /**
     * Check if $user can edit Department
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Department $department
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Department $department)
    {
        return false;
    }

    /**
     * Check if $user can delete Department
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Department $department
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Department $department)
    {
        return false;
    }

    /**
     * Check if $user can view Department
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Department $department
     * @return bool
     */
    public function canView(IdentityInterface $user, Department $department)
    {
        return false;
    }
}
