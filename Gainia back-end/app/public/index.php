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
use Helpers\JwtHelper;

$router = new Router();

// Middleware for admin routes
$router->before('GET|POST|PUT|DELETE', '/admin/.*', function() {
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
    $token = null;

    if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $token = $matches[1];
    }

    if (!$token) {
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(['errorMessage' => 'Admin token not found']);
        exit();
    }

    $payload = JwtHelper::validateToken($token);
    if (!$payload || !isset($payload['role']) || $payload['role'] !== 'admin') {
        header('HTTP/1.1 403 Forbidden');
        echo json_encode(['errorMessage' => 'You do not have permission to access this resource.']);
        exit();
    }
});

// Middleware for JWT validation
$router->before('GET|POST|PUT|DELETE', '/.*', function() {
    $request_uri = $_SERVER['REQUEST_URI'];
    $path_parts = explode('/', parse_url($request_uri, PHP_URL_PATH));
    $base_path_index = array_search('public', $path_parts);
    $api_path = '/';
    if ($base_path_index !== false && isset($path_parts[$base_path_index + 1])) {
        $api_path .= implode('/', array_slice($path_parts, $base_path_index + 1));
    } else if ($base_path_index === false) {
        $api_path = parse_url($request_uri, PHP_URL_PATH);
    }

    if (($_SERVER['REQUEST_METHOD'] === 'POST' && $api_path === '/login') ||
        ($_SERVER['REQUEST_METHOD'] === 'POST' && $api_path === '/user')) {
        return;
    }

    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

    if (!$authHeader) {
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(['errorMessage' => 'Authorization header not found']);
        exit();
    }

    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $jwt = $matches[1];
        if (!$jwt || !JwtHelper::validateToken($jwt)) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(['errorMessage' => 'Invalid or expired token']);
            exit();
        }
    } else {
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(['errorMessage' => 'Bearer token not found in Authorization header']);
        exit();
    }
});

$router->setNamespace('Controllers');

// Admin routes
$router->get('/admin/users', 'UserController@getAll');
$router->delete('/admin/users/{userId}', 'UserController@delete');

//Workout routes
$router->get('/workouts/user/{userId}', 'WorkoutController@getAllWorkoutsByUserId');
$router->get('/workouts/{workoutId}/exercises', 'WorkoutController@getWorkoutExercises');
$router->get('/workouts/{workoutId}', 'WorkoutController@getWorkout');
$router->post('/workouts', 'WorkoutController@createWorkout');
$router->put('/workouts/{workoutId}', 'WorkoutController@updateWorkout');
$router->delete('/workouts/{workoutId}', 'WorkoutController@deleteWorkout');

//Exercise routes
$router->get('/exercises/details/{exerciseId}', 'ExerciseController@getExerciseDetails');
$router->put('/exercises/details/{exerciseId}', 'ExerciseController@updateExerciseDetails');
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
$router->post('/login', 'LoginController@login');

$router->run();