<?php

namespace App\DAO;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UserDao
{
    public function create(array $data)
    {
        Cache::forget("all_users");
        return User::create($data);
    }

    public function update(User $user, array $data)
    {
        $user->update($data);
        Cache::forget("user_{$user->id}");
        Cache::forget("all_users");
        return $user;
    }

    public function find(int $id)
    {
        return Cache::remember("user_{$id}", now()->addMinutes(10), function () use ($id) {
            return User::find($id);
        });
    }

    public function all()
    {
        return Cache::remember('all_users', now()->addMinutes(10), function () {
            Log::info('Fetching all users from the database.');
            return User::all();
        });
    }
}
