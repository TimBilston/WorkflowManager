<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Recurrence;
use Authorization\IdentityInterface;

/**
 * Recurrence policy
 */
class RecurrencePolicy
{
    /**
     * Check if $user can add Recurrence
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Recurrence $recurrence
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Recurrence $recurrence)
    {
    }

    /**
     * Check if $user can edit Recurrence
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Recurrence $recurrence
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Recurrence $recurrence)
    {
    }

    /**
     * Check if $user can delete Recurrence
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Recurrence $recurrence
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Recurrence $recurrence)
    {
    }

    /**
     * Check if $user can view Recurrence
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Recurrence $recurrence
     * @return bool
     */
    public function canView(IdentityInterface $user, Recurrence $recurrence)
    {
    }
}
