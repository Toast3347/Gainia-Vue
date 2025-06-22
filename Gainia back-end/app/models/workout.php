<?php

namespace Models;

class Workout
{
    public int $workout_id;
    public int $user_id;
    public string $workout_date;
    public string $created_at;

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getWorkoutDate(): string
    {
        return $this->workout_date;
    }

    public function setWorkoutDate(string $workout_date): void
    {
        $this->workout_date = $workout_date;
    }
}