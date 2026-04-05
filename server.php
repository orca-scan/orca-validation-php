<?php

// validationHandler is called every time Orca Scan sends a barcode scan to your server.
// It reads the scan data, applies your validation logic, and tells Orca Scan whether
// to save the data, reject it, or change it before saving.
function validationHandler() {

    // Only handle POST requests - return a simple message for everything else.
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(200);
        echo 'Orca Scan Validation Server';
        return;
    }

    // Read the raw request body sent by Orca Scan.
    // This is the JSON data containing the barcode scan and all sheet field values.
    $body = file_get_contents('php://input');

    // Parse the JSON body into an array so you can access any field by name.
    // Fields starting with ___ are Orca system fields (e.g. ___orca_sheet_name).
    // All other fields match your sheet column names exactly (case and spaces matter).
    // For example: $data["Barcode"], $data["Name"], $data["___orca_sheet_name"]
    $data = json_decode($body, true);

    if ($data === null) {
        http_response_code(400);
        return;
    }

    // Get the value of the Name field from the incoming data.
    // If the field is missing, $name will be an empty string.
    $name = isset($data['Name']) ? $data['Name'] : '';

    // ---------------------------------------------------------------
    // OPTION 1: Reject the scan and show an error dialog in the app.
    // Return HTTP 400 with an ___orca_message to block the save and
    // display the message to the user. They must dismiss the dialog
    // before they can try again.
    // ---------------------------------------------------------------
    if (strlen($name) > 20) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode([
            '___orca_message' => [
                'display' => 'dialog',
                'type'    => 'error',
                'title'   => 'Invalid Name',
                'message' => 'Name cannot be longer than 20 characters'
            ]
        ]);
        return;
    }

    // ---------------------------------------------------------------
    // OPTION 2: Modify the data before it saves.
    // Return HTTP 200 with only the fields you want to change.
    // Orca Scan will update those fields and allow the save.
    // ---------------------------------------------------------------
    // header('Content-Type: application/json');
    // http_response_code(200);
    // echo json_encode([
    //     'Name' => $name  // example: you could trim whitespace or reformat the value
    // ]);
    // return;

    // ---------------------------------------------------------------
    // OPTION 3: Show a success notification (green banner in the app).
    // The data still saves - this just gives the user feedback.
    // Return HTTP 200 with an ___orca_message to show the notification.
    // ---------------------------------------------------------------
    // header('Content-Type: application/json');
    // http_response_code(200);
    // echo json_encode([
    //     '___orca_message' => [
    //         'display' => 'notification',
    //         'type'    => 'success',
    //         'message' => 'Barcode scanned successfully'
    //     ]
    // ]);
    // return;

    // ---------------------------------------------------------------
    // SECURITY: Verify the request came from your specific Orca sheet.
    // Set a secret in Orca Scan (Integrations > Events API > Secret)
    // then check it matches here before trusting the data.
    // ---------------------------------------------------------------
    // $secret = isset($_SERVER['HTTP_ORCA_SECRET']) ? $_SERVER['HTTP_ORCA_SECRET'] : '';
    // if ($secret !== getenv('ORCA_SECRET')) {
    //     http_response_code(401);
    //     return;
    // }

    // All good - return HTTP 204 to allow the data to save with no changes.
    // HTTP 204 means "success, no content" - Orca Scan will save the data as-is.
    http_response_code(204);
}

validationHandler();