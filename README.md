# PHP Identity Link

PHP Identity Link is an open-source software solution that empowers the business 
with robust OpenID Connect and OAuth functionality. With support for multiple 
users and clients, as well as the flexibility of multiple secrets per client, 
our project streamlines all standard OAuth flows. Moreover, we provide a set of 
powerful APIs for efficient client and user management.


## Docker image

The application is fully functional and available as a Docker image. To start using it you will have to install [Docker](https://www.docker.com/) and
[Docker compose](https://docs.docker.com/compose/).

The Docker setup relies on [few environment variables](.env.docker.default) used for configuration. Please review
and define these as needed. Refer to [docker compose documentation](https://docs.docker.com/compose/environment-variables/set-environment-variables/)
for more information about environment variables.


##### Start/Stop the containers

To start:

```bash
docker-compose -p phpidlink -f ~/Projects/php-identity-link/docker-compose.yml --env-file ~/.env up
```

To stop all containers:

```bash
docker-compose -p phpidlink -f ~/Projects/php-identity-link/docker-compose.yml --env-file ~/.env down
```

##### Testing

You can execute all tests using the command bellow.

```bash
docker exec -it php-identity-link bash -c "cd /var/www; php bin/phpunit"
```