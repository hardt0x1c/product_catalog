# Product Catalog
API for managing products and categories.

# Tech Stack
- PHP 8.3
- Laravel 11
- MySQL 8

The project only provides an API for managing the product catalog

# Local Deployment
## Environment
After cloning the repository, create .env files with the following commands.

```shell
cp .env.example .env
```

## Sail
sail is used on the project. To deploy containers it is enough to launch them

```shell
./vendor/bin/sail up -d
```

# API Documentation
The project uses Swagger to provide API documentation
To view it, just go to http://localhost/api/v1/documentation
