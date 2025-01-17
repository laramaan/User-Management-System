<?php

namespace App\Services;

use App\BO\UserBo;

class UserService
{
    protected $userBo;

    public function __construct(UserBo $userBo)
    {
        $this->userBo = $userBo;
    }

    public function getUser()
    {
        return $this->userBo->getUser();
    }

    public function createUser(array $data)
    {
        return $this->userBo->createUser($data);
    }

    public function updateUser($user, array $data)
    {
        return $this->userBo->updateUser($user, $data);
    }
}
