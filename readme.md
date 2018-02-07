[![Build Status](https://travis-ci.com/netsells/specification-generator.svg?token=GmksR3KUgtNPYizjuqqy&branch=master)](https://travis-ci.com/netsells/specification-generator)

This project takes a Swagger specification file and generates:

* [ ] models
* [ ] routes
* [ ] controllers
* [x] blade form templates
* [x] database migrations

## Installation

```
composer require juddling/specification-generator --dev
```

## Usage

```
php artisan swagger:generate spec.yaml
```