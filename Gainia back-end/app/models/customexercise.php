<?php

namespace Models;

use DateTime;

class CustomExercise
{
    public int $custom_exercise_id;
    public int $user_id;
    public string $name;
    public string $muscle_group;
    public string $description;
    public DateTime $created_at;
}