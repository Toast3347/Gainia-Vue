<?php

namespace Models;

use DateTime;

class Exercise
{
    public int $exercise_id;
    public string $name;
    public string $muscle_group;
    public string $description;
    public DateTime $created_at;
}