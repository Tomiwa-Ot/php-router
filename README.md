# PHP Router
[![Latest Stable Version](http://poser.pugx.org/grephq/php-router/v)](https://packagist.org/packages/grephq/php-router) [![Total Downloads](http://poser.pugx.org/grephq/php-router/downloads)](https://packagist.org/packages/grephq/php-router) [![Latest Unstable Version](http://poser.pugx.org/grephq/php-router/v/unstable)](https://packagist.org/packages/grephq/php-router) [![License](http://poser.pugx.org/grephq/php-router/license)](https://packagist.org/packages/grephq/php-router)

## Features
- Static routes
- Dynamic routes
- Pass variables in the URI
- Mulitiple HTTP methods
- Custom error handling
- Enable/Disable error reporting
- Request logging

## Usage
### Routes
Routes are defined in ```app.php```
```php
$route->get('/users', function() {
    Index::get();
});

$route->post('/user/<string:name>', function() {
    Index::post();
});

$route->delete('/user/<int:id>', function() {
    Index::delete();
});
```

### Defining Controllers
Controllers are defined in ```Controllers/```
```php
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

## Installation
Via Git
```bash
git clone https://github.com/grephq/php-router.git
```
Via Composer
```bash
composer create-project grephq/php-router
```

## Docs
https://github.com/grephq/php-router/wiki

## Example
https://github.com/grephq/php-router/wiki/Usage#example

## License

MIT License

Copyright (c) 2022 Olorunfemi-Ojo Tomiwa

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

