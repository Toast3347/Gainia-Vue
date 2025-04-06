<?php

namespace Models;

class WorkoutExercise
{
    public int $workout_exercise_id;
    public int $workout_id;
    public int $exercise_id;
    public int $custom_exercise_id;
    public int $sets;
    public int $reps;
    public float $weight;
}