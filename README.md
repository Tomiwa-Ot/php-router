# PHP Router


## Features

## Usage
### Routes
Routes are defined in ```app.php```
```php
$route->get('/users', function() {
    User::get();
});

$route->post('/user/<string:name>', function() {
    User::post();
});

$route->delete('/user/<int:id>', function() {
    User::delete();
});
```

### Defining Controllers
Controllers are defined in ```Controllers/```
```php
require_once __DIR__ . '/BaseController.php';

class User extends BaseController
{
    public static function get()
    {
        render('index.php', array('title' => 'Index Page'));
    }

    public static function post()
    {
        json(reqVar('name'), 200);
    }

    public static function delete()
    {
        xml('Delete Request', 200);
    }
}
```

## Docs
https://github.com/grephq/php-router/wiki

## License


MIT License

Copyright (c) 2022 Olorunfemi-Ojo Tomiwa

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

