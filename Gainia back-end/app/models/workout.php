<?php

namespace Models;
use DateTime;

class Workout
{
    public int $workout_id;
    public int $user_id;
    public DateTime $workout_date;
    public DateTime$created_at;
}