<?php

namespace Models;

use DateTime;

class User
{
    public int $user_id;
    public string $name;
    public string $email;
    public string $password;
    public DateTime $created_at;
}