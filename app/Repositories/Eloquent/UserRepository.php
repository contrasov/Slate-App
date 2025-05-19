<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface {
    
    public function all() {
        return User::all();
    }

    public function find($id){
        return User::findOrFail($id);
    }

    public function create(array $data){
        $data['password'] = bcrypt($data['password']);
        return User::create($data);
    }

    public function update($id, array $data){
        $user =  User::findOrFail($id);
        if(isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        $user = update($data);
        return $user;
    }

    public function delete($id){
        $user = User::findOrFail($id);
        return $user->delete();
    }

}