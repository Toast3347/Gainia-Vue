<?php

namespace Models;

class PR
{
    public int $pr_id;
    public int $user_id;
    public ?int $exercise_id; 
    public ?int $custom_exercise_id; 
    public float $max_weight;
    public int $max_reps;
    public string $record_date; 
    public string $exercise_name;
}