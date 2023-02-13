<?php
use App\Controllers\BookController;
use Core\Router;
new Router();

/*Router::get("/", [BookController::class, "index"]);
Router::get('/create', [BookController::class, 'create']);
Router::post('/', [BookController::class, 'store']);
Router::get('/{id}', [BookController::class, 'show']);
*/

Router::resource('/', BookController::class);





Router::NotFound(function () {
    echo "
        <h1>404</h1>
        <p>Page not found</p>
        <a href='/'>Naar homepagina</a>
    ";
});