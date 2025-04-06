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
    
    public static function fromJson(array $data): self
    {
        $goal = new self();

        $goal->goal_id = $data['goal_id'] ?? 0;
        $goal->user_id = $data['user_id'] ?? 0;
        $goal->exercise_id = $data['exercise_id'] ?? null;
        $goal->custom_exercise_id = $data['custom_exercise_id'] ?? null;
        $goal->target_weight = $data['target_weight'] ?? 0.0;
        $goal->target_reps = $data['target_reps'] ?? 0;
        $goal->deadline = isset($data['deadline']) ? new DateTime($data['deadline']) : new DateTime();
        $goal->achieved = $data['achieved'] ?? false;
        $goal->created_at = isset($data['created_at']) ? new DateTime($data['created_at']) : new DateTime();
        $goal->exercise_name = $data['exercise_name'] ?? '';

        return $goal;
    }
}