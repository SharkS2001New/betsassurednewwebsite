<?php
namespace App\Facades;

class Router {
    private $routes = [];

    // Register GET route
    public function get($path, $callback) {
        $this->addRoute('GET', $path, $callback);
    }

    // Register POST route
    public function post($path, $callback) {
        $this->addRoute('POST', $path, $callback);
    }

    // Add route to the routes array
    private function addRoute($method, $path, $callback) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback,
        ];
    }

    // Handle the incoming request
    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            // Match exact paths and paths with dynamic segments like {date}
            if ($method === $route['method'] && $this->matchPath($route['path'], $requestUri, $params)) {
                call_user_func_array($route['callback'], $params);
                return;
            }
        }

        // Load the custom 404 error page if no route is matched
        http_response_code(404);
        $this->show404Page();
    }

    // Match the request path to the route path
    private function matchPath($routePath, $requestUri, &$params) {
        // Convert the route path to a regex pattern, replacing dynamic segments with capture groups
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $routePath);
        $pattern = "@^" . $pattern . "/?$@"; // Allow optional trailing slash
    
        if (preg_match($pattern, $requestUri, $matches)) {
            array_shift($matches); // Remove the full match from the result
            $params = $matches;     // Capture dynamic segments
            return true;
        }
    
        return false;
    }   

    // Display a 404 error page
    private function show404Page() {
        $errorPagePath = __DIR__ . '/../../pages/404.php';
        if (file_exists($errorPagePath)) {
            include $errorPagePath;
        } else {
            echo "404 Page Not Found";
        }
    }
}
