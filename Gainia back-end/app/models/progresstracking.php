<?php

namespace Models;

class ProgressTracking
{
    public int $progress_id;
    public int $user_id;
    public int $exercise_id;
    public int $custom_exercise_id;
    public int $weight;
    public int $reps;
    public int $record_date;
}