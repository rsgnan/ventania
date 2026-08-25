<?php

namespace App\Model;

class UserModel{
    public int $id;
    public string $name;
    public string $username;
    public string $email;
    public string $password;
    public string $role;
    public int $is_active;
    public ?string $last_ip;
    public ?string $created_at;
    public ?string $updated_at;
}