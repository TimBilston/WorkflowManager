<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Status;
use Authorization\IdentityInterface;

/**
 * Status policy
 */
class StatusPolicy
{
    /**
     * Check if $user can add Status
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Status $status
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Status $status)
    {
        return false;
    }

    /**
     * Check if $user can edit Status
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Status $status
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Status $status)
    {
        return false;
    }

    /**
     * Check if $user can delete Status
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Status $status
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Status $status)
    {
        return false;
    }

    /**
     * Check if $user can view Status
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Status $status
     * @return bool
     */
    public function canView(IdentityInterface $user, Status $status)
    {
        return false;
    }

    protected function isManager(IdentityInterface $user)
    {
        // Manager Id is 2
        return $user->getOriginalData()->get('department_id') == 2;
    }
}
