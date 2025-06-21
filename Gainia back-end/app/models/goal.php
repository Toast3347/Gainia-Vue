<?php

namespace Models;

use DateTime;

class Goal
{
    public int $goal_id;
    public int $user_id;
    public ?int $exercise_id; 
    public ?int $custom_exercise_id; 
    public float $target_weight;
    public int $target_reps;
    public DateTime $deadline;
    public bool $achieved;
    public DateTime $created_at;
    public string $exercise_name;
    


    public function getId(): int
    {
        return $this->goal_id;
    }

    public function getName(): string
    {
        return $this->exercise_name;
    }

    public function setName(string $name): void
    {
        $this->exercise_name = $name;
    }

 
    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->created_at = $createdAt;
    }

    public function getDeadline(): \DateTime
    {
        return $this->deadline;
    }

    public function setDeadline(\DateTime $deadline): void
    {
        $this->deadline = $deadline;
    }

    
}