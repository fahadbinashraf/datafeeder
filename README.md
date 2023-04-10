# Coding Task – Data Feed

A command-line program, based on [Laravel Zero](https://laravel-zero.com/).

## Specifications

-   The program processes a local XML file and pushes the data of that XML file to a DB of choice.
-   Errors are logged to a file
-   Application is tested using PHPUnit

## Installation

### clone the git repository

```bash
git clone git@github.com:fahadbinashraf/datafeeder.git
```

### Setting up the .env file

The database and logging configurations are done using the environment file

Copy the .env.example:

```bash
cp .env.example .env
```

## Running with Docker

The application can be simply executed using docker-compose.

### Prerequisites

-   docker
-   docker-compose

### The XML data file (feed.xml)

The XML file by default should be placed in the /data/feed.xml which is used when running the docker container.

### Running docker-compose

```bash
docker-comopse up --build
```

This will do the following:

-   build the environment
-   install composer dependencies
-   run database migration (to create the table)
-   run unit tests
-   run the import:products command

## Without Docker

Make sure you have following installed:

-   PHP 8.1
-   Composer 2

Install dependencies:

```bash
composer install
```

Run database migrations:

```bash
php datafeeder migrate
```

Run tests:

```bash
php datafeeder test
```

Run the import command:

```bash
php datafeeder import:products /path/to/file.xml
```

## License

[MIT](https://choosealicense.com/licenses/mit/)
