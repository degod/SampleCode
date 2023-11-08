<?php

namespace App\Repository\User;

use App\Models\User;
use Illuminate\Support\Collection;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
interface UserRepositoryInterface
{
    /**
     * The getUser() will get a single User object given
     * the userId as passed in the below param
     * 
     * @param int $userId
     *
     * @return User|null
     */
    public function getUser(int $userId): ?User;

    /**
     * The getUserByUsername() will get a single User object given
     * the username as passed in the below param
     * 
     * @param String $username
     *
     * @return User|null
     */
    public function getUserByUsername(String $username): ?User;

    /**
     * The getUserByEmail() will get a single User object given
     * the email as passed in the below param
     * 
     * @param String $email
     *
     * @return User|null
     */
    public function getUserByEmail(String $email): ?User;

    /**
     * The getReferrals() will get the total number of a user's
     * referrals given the userId as passed in the below param
     * 
     * @param int $userId
     *
     * @return int
     */
    public function getReferrals(int $userId): int;

    /**
     * The createUser() will create a single User in the users table
     * given the data array passed in the below param
     * 
     * @param array $data
     *
     * @return User|null
     */
    public function createUser(array $data): User;

    /**
     * The updateUser() will update a User's record
     * given the userId and data array passed in the below param
     * 
     * @param int $userId
     * @param array $data
     *
     * @return User
     */
    public function updateUser(int $userId, array $data): User;
}