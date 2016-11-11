# Urban Comics

[![Packagist](https://img.shields.io/badge/packagist-install-brightgreen.svg)](https://packagist.org/packages/pgrimaud/urban-comics)
[![Build Status](https://travis-ci.org/pgrimaud/urban-comics.svg?branch=master)](https://travis-ci.org/pgrimaud/urban-comics)
[![Code Climate](https://codeclimate.com/github/pgrimaud/urban-comics/badges/gpa.svg)](https://codeclimate.com/github/pgrimaud/urban-comics)
[![Test Coverage](https://codeclimate.com/github/pgrimaud/urban-comics/badges/coverage.svg)](https://codeclimate.com/github/pgrimaud/urban-comics/coverage)

Little scraper for http://www.urban-comics.com/

## Usage

```
composer require pgrimaud/urban-comics
```

```php
$api = new \UrbanComics\Scraper();

$parameters = [
    'month' => 'janvier',
    'year' => '2012'
];

$comics = $api->getComics($parameters);
```

Will return : 

```
Array
(
    [0] => Array
        (
            [date] => 20 JANVIER
            [image] => http://www.urban-comics.com/wp-content/uploads/2015/05/watchmen_cover-270x412.jpg
            [link] => http://www.urban-comics.com/watchmen-lintegrale/
            [title] => WATCHMEN –
            [title_from_link] => Watchmen Lintegrale
            [type] => WATCHMEN
            [authors] => Array
                (
                    [0] => Array
                        (
                            [position] => Scénario
                            [name] => Moore Alan
                        )

                    [1] => Array
                        (
                            [position] => Dessin
                            [name] => Gibbons Dave
                        )

                )

        )

)
```