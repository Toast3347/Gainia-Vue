<?php

namespace Models;

use DateTime;

class CustomExercise
{
    public ?int $custom_exercise_id = null;
    public int $user_id;
    public string $name;
    public string $muscle_group;
    public string $description;
    public ?string $created_at = null;
}