# orca-validation-php

Example of [how to validate barcode scans in real-time](https://orcascan.com/guides/how-to-validate-barcode-scans-in-real-time-56928ff9) using [Php](https://www.php.net).

## Install

First ensure you have [Php](https://www.php.net/manual/en/install.php) installed.
```bash
# should return 7.1 or higher
php -v
```

Then execute the following:

```bash
# download this example code
git clone https://github.com/orca-scan/orca-validation-php.git

# go into the new directory
cd orca-validation-php
```

## Run

```bash
# run php server
php -S 127.0.0.1:8000 server.php
```

Server will now be running on port 8000.

You can emulate an Orca Scan Validation input using [cURL](https://dev.to/ibmdeveloper/what-is-curl-and-why-is-it-all-over-api-docs-9mh) by running the following:

```bash
curl --location --request POST 'http://127.0.0.1:8000/' \
--header 'Content-Type: application/json' \
--data-raw '{
    "___orca_sheet_name": "Vehicle Checks",
    "___orca_user_email": "hidden@requires.https",
    "Barcode": "orca-scan-test",
    "Date": "2022-04-19T16:45:02.851Z",
    "Name": "Orca Scan Validation"
}'
```
### Important things to note

1. Only Orca Scan system fields start with `___`
2. Properties in the JSON payload are an exact match to the  field names in your sheet _(case and space)_

## Validation example

This [example](server.php) uses the [Laravel](https://laravel.com/) framework:

```php
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

        //return HTTP Status 204 (No Content)
        http_response_code(204);
        exit;
    }
}
```
## Test server locally against Orca Cloud

To expose the server securely from localhost and test it easily against the real Orca Cloud environment you can use [Secure Tunnels](https://ngrok.com/docs/secure-tunnels#what-are-ngrok-secure-tunnels). Take a look at [Ngrok](https://ngrok.com/) or [Cloudflare](https://www.cloudflare.com/).

```bash
ngrok http 8000
```

## Troubleshooting

If you run into any issues not listed here, please [open a ticket](https://github.com/orca-scan/orca-validation-php/issues).

## Examples in other langauges
* [orca-validation-dotnet](https://github.com/orca-scan/orca-validation-dotnet)
* [orca-validation-python](https://github.com/orca-scan/orca-validation-python)
* [orca-validation-go](https://github.com/orca-scan/orca-validation-go)
* [orca-validation-java](https://github.com/orca-scan/orca-validation-java)
* [orca-validation-php](https://github.com/orca-scan/orca-validation-php)
* [orca-validation-node](https://github.com/orca-scan/orca-validation-node)

## History

For change-log, check [releases](https://github.com/orca-scan/orca-validation-php/releases).

## License

&copy; Orca Scan, the [Barcode Scanner app for iOS and Android](https://orcascan.com).