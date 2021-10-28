<?php
declare(strict_types=1);

namespace App\Domain\User;

interface UserRepository
{
    /**
     * Insert or update an User
     *
     * @param User $user
     */
    public function save(User $user): void;

    /**
     * @param int|null $user_id
     * @return bool
     */
    public function userExists(?int $user_id): bool;

    /**
     * Delete User of id
     *
     * @param int $user_id
     */
    public function delete(int $user_id): void;

    /**
     * @return User[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function findUserOfId(int $id): User;
}
