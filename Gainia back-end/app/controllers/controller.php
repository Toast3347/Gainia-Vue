<?php

namespace Controllers;


class Controller
{
    public function respond($data){
        return $this->respondWithCode(200, $data);
    }

    public function respondCreated($data)
    {
        return $this->respondWithCode(201, $data);
    }


    private function respondWithCode($httpcode, $data)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($httpcode);
        echo json_encode($data, JSON_UNESCAPED_SLASHES);
    }

    public function respondWithError($httpcode, $message)
    {
        $data = array('errorMessage' => $message);
        $this->respondWithCode($httpcode, $data);
    }

    function createObjectFromPostedJson($className)
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json);

        $object = new $className();
        foreach ($data as $key => $value) {
            $reflection = new \ReflectionClass($object);
            if ($reflection->hasProperty($key)) {
                $property = $reflection->getProperty($key);
                $type = $property->getType();
                if ($type && $type->getName() === 'DateTime' && is_string($value)) {
                    $object->{$key} = new \DateTime($value);
                } else {
                    $object->{$key} = $value;
                }
            } else {
                $object->{$key} = $value;
            }
        }
        return $object;
    }
}