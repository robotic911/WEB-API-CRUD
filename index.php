<?php

include "../coolest_api/config/db.php";

header(header: "Content-Type: application/json");

//GET PUT POST DELETE UPDATE
$requestMethod = $_SERVER["REQUEST_METHOD"];

//TO HANDLE IF REQUEST DOES NOT EXIST
$request = isset($_GET['request']) ? explode("/", trim($_GET['request'], "/")) : [];
$id = isset($_GET['id']) ? $_GET['id'] : null;
$update = isset($_GET['title']) ? $_GET['description'] : null;

//HOW TO HANDLE HTTP VERB
$requestMethod;

switch ($requestMethod) {
    case 'POST':
        createTask();
        break;

    case 'GET':
        getTask($id);
        break;

    case 'PATCH':
        updateTask($id);
        break;  
    
    case 'PUT':
        updateTasks();
        break;  

    case 'DELETE':
        deleteTask($id);
        break;  

    default:
        http_response_code(405);
        echo json_encode(["message" => "Method not existing"]);
        break;
}

mysqli_close($connection);
?>

<?php

//POST METHOD FUNCTION
function createTask()
{
    global $connection;
    $data = json_decode(file_get_contents("php://input"), true);
    $title = $data['title'];
    $description = $data['description'];

    if (!empty($title)) {

        $sql = "INSERT INTO task (title, description) VALUES ('$title', '$description')";
        if (mysqli_query($connection, $sql)) {
            http_response_code(201);
            
            echo json_encode(["Message" => "Task Created Successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["Message" => "Error creating task"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["Message" => "Title is required"]);
    }
}

function getTask($id)
{
    global $connection;
    $data = json_decode(file_get_contents("php://input"), true);
    
    if ($id !== NULL){
        $sql = "SELECT * from task WHERE id = ('$id')";
        if (mysqli_query($connection, $sql)) {
            $result = mysqli_query($connection, $sql);
            $task = mysqli_fetch_all($result, MYSQLI_ASSOC);
            echo json_encode([$task]);
        }else {
                http_response_code(500);
                echo json_encode(["Message" => "Error displaying task"]);        
        }

    }else{
        $sql = "SELECT * from task";
        if (mysqli_query($connection, $sql)) {
            $result = mysqli_query($connection, $sql);
            $task = mysqli_fetch_all($result, MYSQLI_ASSOC);
            echo json_encode([$task]);
        }else{
            http_response_code(500);
            echo json_encode(["Message" => "Error displaying task"]);}
        }
}  

function updateTasks()
{
    global $connection;
    $data = json_decode(file_get_contents("php://input"), true);
    $title = $data['title'];
    $description = $data['description'];
    $sql = "UPDATE task SET title = '$title', description = '$description'";
    
        if (mysqli_query($connection, $sql)) {
            $result = mysqli_query($connection, $sql);
            echo json_encode(["Message" => "ALL Data is Updated Successfully"]);
        }
         else {
            http_response_code(500);
            echo json_encode(["Message" => "Error creating task"]);
        }

}  

function updateTask($id)
{
    global $connection;
    $data = json_decode(file_get_contents("php://input"), true);
    $title = $data['title'];
    $description = $data['description'];
    $sql = "UPDATE task SET title = '$title', description = '$description' WHERE id = ('$id') ";
    
        if (mysqli_query($connection, $sql)) {
            $result = mysqli_query($connection, $sql);
            echo json_encode(["Message" => "The Data is Updated Successfully"]);
        }
         else {
            http_response_code(500);
            echo json_encode(["Message" => "Error creating task"]);
        }

}   


function deleteTask($id)
{
    global $connection;
    $data = json_decode(file_get_contents("php://input"), true);

    if ($id !== NULL) {
        $sql = "DELETE FROM task WHERE id =('$id')";
        if (mysqli_query($connection, $sql)) {

            echo json_encode(["Message" => "Data ID Deleted Successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["Message" => "Error creating task"]);
        }
    }else{
        $sql = "DELETE from task";
        if (mysqli_query($connection, $sql)) {
            echo json_encode(["Message" => "ALL Data is Deleted Successfully"]);

        }else {
            http_response_code(500);
            echo json_encode(["Message" => "Error creating task"]);        
        }  
    }
}
?>