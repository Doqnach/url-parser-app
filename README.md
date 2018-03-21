# URL Parser App

Simple web-app showcasing the working of [doqnach/helper-url](https://github.com/Doqnach/helper-url).

## Getting started

Make sure all [composer](https://getcomposer.org) packages are installed by running the following command inside the `url-parser-app/` directory:

```bash
$ composer install
```

## How to start using docker

This expects you to have Docker running already.

### Test environment

To run the test environment, which starts the webserver on port `60080`:
```bash
$ docker-compose -f docker-compose.yml -f docker-compose.tst.yml up
```

### Production-like environment

To run the production-like environment, which starts the webserver on port `80`:
```bash
$ docker-compose -f docker-compose.yml -f docker-compose.prd.yml up
```

### Traefik

If you have [Traefik](https://traefik.io) running in your docker environment, use the following `docker-compose.override.yml`:

```yaml
version: '3'
services:
  php:
    labels:
      - "traefik.enable=false"
  nginx:
    labels:
      - "doqnach-urlparserapp_nginx"
      - "traefik.frontend.rule=Host:urlparserapp.devel"
networks:
  default:
    external:
      name: traefik_webgateway
```

And start with:

```bash
$ docker-compose up -d
```