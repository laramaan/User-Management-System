<?php

namespace App\BO;

use App\DAO\UserDao;

class UserBo
{
    protected $userDao;

    public function __construct(UserDao $userDao)
    {
        $this->userDao = $userDao;
    }

    public function getUser()
    {
        return $this->userDao->all();
    }

    public function createUser(array $data)
    {
        $data['password'] = bcrypt($data['password']);
        return $this->userDao->create($data);
    }

    public function updateUser($id, array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        return $this->userDao->update($id, $data);
    }
}
