<?php

header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type");
    header('Content-Type: application/json');

    $mysqli = new mysqli('localhost', 'root', "", "news database");

    if($mysqli->connect_error){
        die("Connection Error (" . $mysqli->connect_errno . ')' . $mysqli->connect_error);
    }


    $request_method = $_SERVER["REQUEST_METHOD"];

    switch ($request_method) {
        case 'GET':
                $response = getAllNews();
                echo json_encode($response);
            break;


        case 'POST':
            if(!empty($_POST["text"]) && !empty($_POST["type"])){
                $text= $_POST["text"];
                $type= $_POST["type"];
                $response = createNew($text,$type);
                echo json_encode($response);
            }else{
                echo json_encode([
                    "status"=>"text is requried",
                ]);
            }

            break;
            
        default:
            echo json_encode([
                "status"=>"something went wrong",
            ]);
            break;
    }

    function createNew($text,$type){
        global $mysqli;
        $response;
        $query = $mysqli->prepare("INSERT INTO news (text,type) VALUES (?,?)");
        $query->bind_param("ss", $text,$type);
        if($query->execute()){
            $response["status"] = "Success";
        }else{
            $response["status"] = "Failed";
        }

        return $response;
    }

    function getAllNews(){
        global $mysqli;
        $query = $mysqli->prepare("SELECT * FROM news");
        $query->execute();
        $query->store_result();
        $num_rows = $query->num_rows();

        if($num_rows == 0) {
            $response["status"] = "No news";
        }else{
            $news = [];
            $query->bind_result($id,$type,$text);
            while($query->fetch()){
                $new = [
                    'id' => $id,
                    'type' => $type,
                    'text' => $text
                ];

                $news[] = $new;
            }

            $response["status"] = "Success";
            $response["news"] = $news;
        }

        return $response;
    }