# Parserator

[![Build Status](https://travis-ci.com/netsells/specification-generator.svg?token=GmksR3KUgtNPYizjuqqy&branch=master)](https://travis-ci.com/netsells/specification-generator)

This project parses a Swagger 2.0 specification file and generates Laravel:

* [ ] models
* [ ] routes
* [ ] requests
* [ ] model factories
* [ ] controllers
* [x] blade form templates
* [x] database migrations

## Installation
Run:
```
composer require juddling/specification-generator --dev
```
Laravel 5.4 and below add the following service provider to `config/app.php`:
```
\Juddling\Parserator\GeneratorServiceProvider::class,
```
## Usage

```
php artisan swagger:generate spec.yaml
```