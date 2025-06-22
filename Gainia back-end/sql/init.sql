CREATE DATABASE GainiaDB;
USE GainiaDB;

CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    role ENUM('admin', 'user') DEFAULT 'user'
);

CREATE TABLE Exercises (
    exercise_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    muscle_group ENUM('Abs', 'Back', 'Biceps', 'Cardio', 'Chest', 'Legs', 'Shoulders', 'Triceps') NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE CustomExercises (
    custom_exercise_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    muscle_group ENUM('Abs', 'Back', 'Biceps', 'Cardio', 'Chest', 'Legs', 'Shoulders', 'Triceps') NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

CREATE TABLE Workouts (
    workout_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    workout_date DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

CREATE TABLE WorkoutExercises (
    workout_exercise_id INT AUTO_INCREMENT PRIMARY KEY,
    workout_id INT NOT NULL,
    exercise_id INT,
    custom_exercise_id INT,
    sets INT NOT NULL,
    reps INT NOT NULL,
    weight DECIMAL(5,2) NOT NULL,
    FOREIGN KEY (workout_id) REFERENCES Workouts(workout_id) ON DELETE CASCADE,
    FOREIGN KEY (exercise_id) REFERENCES Exercises(exercise_id) ON DELETE SET NULL,
    FOREIGN KEY (custom_exercise_id) REFERENCES CustomExercises(custom_exercise_id) ON DELETE SET NULL
);

CREATE TABLE ProgressTracking (
    progress_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    exercise_id INT,
    custom_exercise_id INT,
    weight DECIMAL(5,2),
    reps INT,
    record_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (exercise_id) REFERENCES Exercises(exercise_id) ON DELETE SET NULL,
    FOREIGN KEY (custom_exercise_id) REFERENCES CustomExercises(custom_exercise_id) ON DELETE SET NULL
);

CREATE TABLE Goals (
    goal_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    exercise_id INT,
    custom_exercise_id INT,
    target_weight DECIMAL(5,2),
    target_reps INT,
    deadline DATE NOT NULL,
    achieved BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (exercise_id) REFERENCES Exercises(exercise_id) ON DELETE SET NULL,
    FOREIGN KEY (custom_exercise_id) REFERENCES CustomExercises(custom_exercise_id) ON DELETE SET NULL
);

CREATE TABLE PersonalRecords (
    pr_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    exercise_id INT,
    custom_exercise_id INT,
    max_weight DECIMAL(5,2) NOT NULL,
    max_reps INT NOT NULL,
    record_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (exercise_id) REFERENCES Exercises(exercise_id) ON DELETE SET NULL,
    FOREIGN KEY (custom_exercise_id) REFERENCES CustomExercises(custom_exercise_id) ON DELETE SET NULL
);

--test data
INSERT INTO Users (name, email, password, role) VALUES
('john doe', 'john@example.com', '$2y$12$hYN/CzuBTKAn.VCTLxKz.ORINGNlK98qtLWHNcTMCR2RVG.KnSqLG', 'admin'),
('jane smith', 'jane@example.com', '$2y$12$hYN/CzuBTKAn.VCTLxKz.ORINGNlK98qtLWHNcTMCR2RVG.KnSqLG', 'user');

INSERT INTO Exercises (name, muscle_group, description) VALUES
('Bench Press', 'Chest', 'A classic chest exercise using a barbell or dumbbells'),
('Deadlift', 'Back', 'A full-body exercise primarily targeting the lower back'),
('Squat', 'Legs', 'A fundamental lower body exercise working quads and glutes');

INSERT INTO CustomExercises (user_id, name, muscle_group, description) VALUES
(1, 'Incline Dumbbell Press', 'Chest', 'A chest exercise targeting upper pectorals'),
(2, 'Bulgarian Split Squat', 'Legs', 'A single-leg squat variation for unilateral strength');

INSERT INTO Workouts (user_id, workout_date) VALUES
(1, '2025-03-30 10:00:00'),
(2, '2025-03-31 17:30:00');

INSERT INTO WorkoutExercises (workout_id, exercise_id, custom_exercise_id, sets, reps, weight) VALUES
(1, 1, NULL, 4, 10, 80.00),
(1, NULL, 1, 3, 12, 30.00),
(2, 2, NULL, 3, 8, 100.00);

INSERT INTO ProgressTracking (user_id, exercise_id, custom_exercise_id, weight, reps, record_date) VALUES
(1, 1, NULL, 85.00, 8, '2025-03-30 10:30:00'),
(1, 1, NULL, 110.00, 1, '2025-04-5 10:30:00'),
(2, NULL, 2, 40.00, 10, '2025-03-31 18:00:00');

INSERT INTO Goals (user_id, exercise_id, custom_exercise_id, target_weight, target_reps, deadline) VALUES
(1, 1, NULL, 100.00, 10, '2025-06-01'),
(2, NULL, 2, 50.00, 12, '2025-07-15');

INSERT INTO PersonalRecords (user_id, exercise_id, custom_exercise_id, max_weight, max_reps, record_date) VALUES
(1, 1, NULL, 90.00, 6, '2025-03-28 09:45:00'),
(2, NULL, 2, 45.00, 8, '2025-03-29 16:20:00');
