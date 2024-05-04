# Laravel Calculator App
## Overview
This Laravel application implements a basic calculator capable of handling essential arithmetic operations. + - * / 

## Features
- **Basic Arithmetic Operations**: Perform addition, subtraction, multiplication, and division.
- **Validation**: Ensures only valid mathematical expressions are processed. and **displays the error in the expression**.

## Installation

### Docker (Makefile commands)
1. Clone the repository.
2. Run `make docker-up` to start the Docker containers.
3. Run `make docker-app` to access the application container.
4. Run `composer install` to install the dependencies.
```
make docker-up
make docker-app
composer install
```
5. Access the application at `http://localhost:8080`.

### Local
1. Clone the repository.
2. Run `composer install` to install the dependencies.
3. Run `php artisan serve` to start the application.
```
composer install
php artisan serve
```
4. Access the application at `http://localhost:8000`.

## Usage

![calculator error](https://i.ibb.co/XXZ0Bby/calcerr.png")

1. Enter a mathematical expression in the input field.
2. Click the `=` button to calculate the result.
3. The result will be displayed below the input field.
4. expession errors will be displayed in the error field showing the error in the expression.
here are some examples of valid expressions:
- `4 * 3 / 2 (6 + 2)`
- `2 * (3 + 4)`
- `2+3(76+49*5)+54+(42/2)`

here are some examples of invalid expressions:
- `2 * 3 / 0`
- `2 * (3 + 4`
- `2 + 3) * 4`

