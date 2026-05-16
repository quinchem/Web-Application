<?php

class User
{
    public $user_id;

    public $role_id;

    public $user_name;

    public $email;

    public $password;

    public $full_name;

    public $gender;

    public $avatar;

    public $account_status;

    public $created_at;

    public $deleted_at;


    public function __construct($data = [])
    {
        $this->user_id = $data['user_id'] ?? null;

        $this->role_id = $data['role_id'] ?? null;

        $this->user_name = $data['user_name'] ?? '';

        $this->email = $data['email'] ?? '';

        $this->password = $data['password'] ?? '';

        $this->full_name = $data['full_name'] ?? '';

        $this->gender = $data['gender'] ?? '';

        $this->avatar = $data['avatar'] ?? '';

        $this->account_status = $data['account_status'] ?? 'active';

        $this->created_at = $data['created_at'] ?? null;

        $this->deleted_at = $data['deleted_at'] ?? null;
    }
}