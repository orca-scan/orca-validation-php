<?php
//Validation Controller
if (preg_match('/$/', $_SERVER["REQUEST_URI"])){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        // NOTE:
        // orca system fields start with ___
        // you can access the value of each field using the field name (data.Name, data.Barcode, data.Location)        $name = $data["Name"];
        $name = $data["Name"];

        // validation example
        if (strlen($name) > 20) {
            // return json error message
            echo json_encode(array(
                "title" => "Invalid Name",
                "message" => "Name cannot contain more than 20 characters"
            ));
            exit;
        }

        //return HTTP Status 200 with no body
        http_response_code(200);
        exit;
    }
}
else {
    echo "<p>Welcome to Orca Validation Example.</p>";
}

?>