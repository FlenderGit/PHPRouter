# PHP Simple Router

## Initialization

### Create a router
In the `index.php`, you need to create a new controller.
```php
$router = new Router();
```
That will be the base of the website.

Now, the router is ready, we can go to the Utilisation part if you want to add routes or read the text following to configure some parameters.

### Configuration parameters

In the config.php, you have severals parameters.

#### Database parameters
- DB_NAME => The name of the database
- DB_USER => The name of the user
- DB_PASSWORD => The password for the user
- DB_HOST => The host of the database

#### Routing parameters
- AUTOWIRING => Allow to add parameters inside route function definition and let Router initialize them. (E.g. PDO, Repository...)
- AUTOLOAD_CONTROLLER => Allow the router to scan the Controller Repository and add all Controller inside.


## Utilisation

### Create route from anonymous function

#### Simple route
Below the router initialisation you can add routes.
```php
$router->get("/home", function() {
    return "<h1>Hello world !</h1>";
});
```

#### Route with parameters
To add parameters, you need to add a ':name' in your URL and the same name in the function's parameter.
```php
$router->get("/home/:name", function(string $name) {
    return "<h1>Hello $name !</h1>";
});
```

### Create route from Controllers


#### Create a Controller
First at all, you need to create a Controller. It's recommended to create the file in the Controller folder.
```php
class HomeController extends Controller {
    // Code...
}
```

If your Controller is the in the recommended folder or you have not set the parameter AUTOLOAD_CONTROLLER to true, you need the register the Controller. In your index.php :
```php
$router->scanController(HomeController::class);
```

#### Create a route

Inside your Controller, you can create a method. You need to use annotation to specifie route parameters.
```php
#[Route("GET", "/home/:id")]
public function number(int $id) {
    return "Hello n°$id !";
}
```
All details about route cutomisations of route from anonymous function are the same.

### Responses

It's time to see all responses that a Route can return.

#### Default
Default is a string, simple but too simple...
```php
return "Hello world !";
```

#### HtmlResponse
You can also return simple HTML element
```php
return new HtmlResponse("<h1>Hello World !</h1>");
```

#### JsonResponse
It's possible to return JSON for API
```php
return new JsonResponse(["text" => "Hello world !"]);
```

#### RedirectResponse
```php
return new RedirectResponse("/home");
```

#### Rendering template
For complexe webpage, it is possible to use the render function. However, we try to build a template engine to make things easier. The array is the parameter representing $variables to send to the template.
```php
return $this->render("home", array(
    "title" => "Home world"
));
```

### Error handler
You can add a response if the website detect a error during routing.
```php
$router->error(function(Exception $e) {
    return new HtmlResponse("<h1>ERROR : {$e->getMessage()}</h1>");
});
```

## To do
- Template engine
- Repository
- public / static