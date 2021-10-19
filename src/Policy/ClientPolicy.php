<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Client;
use Authorization\IdentityInterface;

/**
 * Client policy
 */
class ClientPolicy
{
    /**
     * Check if $user can add Client
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Client $client
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Client $client)
    {
        return true;
    }

    /**
     * Check if $user can edit Client
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Client $client
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Client $client)
    {
        return $this->isManager($user);
    }

    /**
     * Check if $user can delete Client
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Client $client
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Client $client)
    {
        return $this->isManager($user);
    }

    /**
     * Check if $user can view Client
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Client $client
     * @return bool
     */
    public function canView(IdentityInterface $user, Client $client)
    {
        return true;
    }

    protected function isManager(IdentityInterface $user)
    {
        // Manager Id is 2
        return $user->getOriginalData()->get('department_id') == 2;
    }
}
