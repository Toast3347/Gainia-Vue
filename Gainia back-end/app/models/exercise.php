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

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function getMuscleGroup(): string
    {
        return $this->muscle_group;
    }
    public function setMuscleGroup(string $muscle_group): void
    {
        $this->muscle_group = $muscle_group;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }
    public function setCreatedAt(DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }
    public function getExerciseId(): int
    {
        return $this->exercise_id;
    }
    public function setExerciseId(int $exercise_id): void
    {
        $this->exercise_id = $exercise_id;
    }
    public function setDateTime(string $dateTime): void
    {
        $this->created_at = new DateTime($dateTime);
    }
    public function getDateTime(): string
    {
        return $this->created_at->format('Y-m-d H:i:s');
    }
}