<?php

namespace App\Repository\User;

use App\Repository\User\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
class UserRepository implements UserRepositoryInterface 
{
    /**
     * @var User
     */
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * The getUser() will get a single User object given
     * the userId as passed in the below param
     * 
     * @param int $userId
     *
     * @return User|null
     */
    public function getUser(int $userId): ?User
    {
    	$user = $this->user->find($userId);

        return $user;
    }

    /**
     * The getUserNetworks() will get a User's network object given
     * the userId as passed in the below param
     * 
     * @param int $userId
     *
     * @return object|null
     */
    public function getUserNetworks(int $userId): ?object
    {
        $user = $this->user->find($userId);

        return $user->networks;
    }

    /**
     * The getUserByUsername() will get a single User object given
     * the username as passed in the below param
     * 
     * @param String $username
     *
     * @return User|null
     */
    public function getUserByUsername(String $username): ?User
    {
        $user = $this->user->whereUsername($username)->first();

        return $user;
    }

    /**
     * The getUserByEmail() will get a single User object given
     * the email as passed in the below param
     * 
     * @param String $email
     *
     * @return User|null
     */
    public function getUserByEmail(String $email): ?User
    {
        $user = $this->user->whereEmail($email)->first();

        return $user;
    }

    /**
     * The getReferrals() will get the total number of a user's
     * referrals given the userId as passed in the below param
     * 
     * @param int $userId
     *
     * @return int
     */
    public function getReferrals(int $userId): int
    {
        $user = $this->user->where('referrer_user_id',$userId)->count();

        return $user;
    }

    /**
     * The createUser() will create a single User in the users table
     * given the data array passed in the below param
     * 
     * @param array $data
     *
     * @return User|null
     */
    public function createUser(array $data): User
    {
    	$user = $this->user->create($data);

        return $user;
    }

    /**
     * The updateUser() will update a User's record
     * given the userId and data array passed in the below param
     * 
     * @param int $userId
     * @param array $data
     *
     * @return User
     */
    public function updateUser(int $userId, array $data): User
    {
        $user = $this->user->findOrFail($userId);
        $user->update($data);

        return $user;
    }
}