# Simple Framework

For rapid development of simple web apps.


## Constraints
1. There must be NO permanent running processes. The entire full-stack app must use solely PHP on the backend.
2. Sites should be as simple to develop as possible.
3. There should be minimal code needing to be written.

## Framework Details

### Frontend

[headless-cms](https://github.com/Littled2/headless-cms) will serve the frontend.

#### Styling

Page-specific styles should go in the `styles.css` file.

Global styles should go in the `/resources/styles/`directory and be split into:
- `globals.css` for global styles, `<html/>`.
- `header.css` for styles relating to the `<header/>`.
- `utils.css` for re-usable general utility classes to be used throughout.

Responsive styling should go at the bottom of the file where the css rules that it is changing are. 

#### Interactivity

- [Alpine JS](https://alpinejs.dev/) should be used for all DOM manipulation as much as possible.

- Any extra JavaScript should be stored in the `/scripts` directory

#### Icons

Image based icons should be avoided in favour of unicode characters for icons. [Find an icon here](https://www.compart.com/en/unicode/).
 

##### HTTP Requests

Making requests from the frontend should be done using the customised alpine-requests extension for Alpine JS. See the original extension documentation [here](https://github.com/0wain/alpinejs-requests).

 

#### Other Resources (Images etc.)

These should be stored in the `/resources` directory. Other subdirectories are project dependent.

 

 

### Backend

All backend code should be stored in the `/backend` directory, and written in PHP.

 

#### API Routes

API routes shoudl be declared in the `/backend/api` directory. The use of subdirectories within `/backend/api` is project dependant.

 

All API routes should follow the following rules:

1. Each route should have its own PHP file.

2. Each route PHP file should be named as such `{HTTP METHOD}-{relevant entity}.php`. Eg. `GET-person.php`.

3. Each route MUST: Check the correct HTTP method, validate any sent data, return response (often from database).

 

 

##### Helpers

There are a number of helper functions for simplifying API route development. The helper functions are all written in `/backend/helpers/helpers.php`

 

The functions are as follows:

 

| Function | Use |
| -------- | --- |
| http_method_must_be() | Checks the HTTP method is correct |
| validate_request_data() | Checks requested data has been provided |
| send_response() | Responds with a HTTP code, message and exits |

 

Examples:

```php

// Check HTTP method
http_method_must_be("POST"); // Fails if method is not POST (Works with GET also)

// Validate incoming data
validate_request_data($_POST, "name|string", "age|number"); // Fails of either name or age has not been provided (Works with $_GET also)

// Responds and exits
send_response(500, "Something went wrong server-side");

```


#### Database
Any database implementation must only be run from PHP, and not use its own process. The ideal database is demand-db, written for exactly this task.

##### demand-db
All database methods are contained within the `/backend/database/demand-db.php` file.

- To use the database, import it, then initialise with `new DemandDB()`.
- See demand-db documentation for further instructions.


```php

$db = new DemandDB()

// Create a task
$taskID = $database->create_document("tasks", $data);

// Get all documents in the 'tasks' collection
$tasks = $database->get_documents("tasks")->documents;

// Get all tasks that have the label 'urgent'
$tasks = $database->get_documents("tasks")->where("label", "=", "urgent")->documents;

// Get a task by it's ID
$task = $database->get_document("tasks", "task_id");

// Update a task
$database->update_document("tasks", "task_id", $data);

// Delete a task
$database->delete_document("tasks", "task_id");

```

 

#### Authentication
Authentication should be implemented using PHP with session based auth.

See the following code example:

```php

$db = new DemandDB("....")

$user = $database->get_documents("users")->where("username", "=", 

$_POST["username"])->first();

if($user === null) {
    http_response_code(400);
    echo "User does not exist";
    exit;
}

// Check the password
if(!password_verify($password, $user['password']) {
    http_response_code(400);
    echo "Incorrect password";
    exit;
}

session_start();

$_SESSION["username"] = $user["username"];

```