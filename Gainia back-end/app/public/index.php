<?php
//CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
//This is unsafe and should be changed
//For now it's fine because it isn't a website that will go live :)

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once __DIR__ . '/../vendor/autoload.php';

use Bramus\Router\Router;

$router = new Router();

$router->setNamespace('Controllers');

//Workout routes
$router->get('/workouts/user/{userId}', 'WorkoutController@getAllWorkoutsByUserId');
$router->get('/workouts/{workoutId}/exercises', 'WorkoutController@getWorkoutExercises');
$router->post('/workouts', 'WorkoutController@createWorkout');
$router->put('/workouts/{workoutId}', 'WorkoutController@updateWorkout');
$router->delete('/workouts/{workoutId}', 'WorkoutController@deleteWorkout');

//Exercise routes
$router->get('/exercises/standard', 'ExerciseController@getAllStandardExercises');
$router->get('/exercises/custom/user/{userId}', 'ExerciseController@getAllCustomExercisesByUserId');
$router->post('/exercises/standard', 'ExerciseController@createStandardExercise');
$router->post('/exercises/custom', 'ExerciseController@createCustomExercise');
$router->put('/exercises/custom/{exerciseId}', 'ExerciseController@updateCustomExercise');
$router->delete('/exercises/custom/{exerciseId}', 'ExerciseController@deleteCustomExercise');
$router->put('/exercises/standard/{exerciseId}', 'ExerciseController@updateStandardExercise');
$router->delete('/exercises/standard/{exerciseId}', 'ExerciseController@deleteStandardExercise');
$router->get("/exercises/all/user/{userId}", "ExerciseController@getAllExercisesByUserId");

//Pr routes
$router->get('/pr/user/{userId}', 'PrController@getAllPrs');
$router->post('/pr', 'PrController@createPr');
$router->put('/pr/{prId}', 'PrController@updatePr');
$router->delete('/pr/{prId}', 'PrController@deletePr');

//Goal routes
$router->get('/goals/user/{userId}', 'GoalController@getAllGoals');
$router->post('/goals', 'GoalController@createGoal');
$router->put('/goals/{goalId}', 'GoalController@updateGoal');
$router->delete('/goals/{goalId}', 'GoalController@deleteGoal');

//Progress routes
$router->get('/progress/user/{userId}', 'ProgressController@getAllProgress');
$router->post('/progress', 'ProgressController@createProgress');
$router->put('/progress/{progressId}', 'ProgressController@updateProgress');
$router->delete('/progress/{progressId}', 'ProgressController@deleteProgress');


//login routes
$router->get('/user/{username}', 'LoginController@getUserByUsername');
$router->post('/user', 'LoginController@createUser');


$router->run();