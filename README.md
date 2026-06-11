# Tudor E-Stock client bundle

Symfony bundle that provides an api client for Tudor E-Stock API

## Installation

Install this bundle using composer:

```sh
composer require idci/tudor-estock-client-bundle
```

## Configuration

### Create an Eightpoint Guzzle HTTP client

In the file `config/packages/eight_points_guzzle.yaml`, create a **Tudor E-Stock API** client :

```yaml
eight_points_guzzle:
    clients:
        tudor_estock_api:
            base_url: 'https://pp-api.services.mytudorwatch.com/estock-retail/retailer' # PROD = 'https://api.services.mytudorwatch.com/estock-retail/retailer'
```

### Configure a cache pool

Create a dedicated **Tudor E-Stock** cache, or use any of your existing pools :

In the file `config/services.yaml`, register your cache pool :

```yaml
# Redis example
app.cache.adapter.redis.tudor_estock:
    parent: 'cache.adapter.redis'
    tags:
        - { name: 'cache.pool', namespace: 'tudor_estock' }
```

In the file `config/packages/cache.yaml`, define your cache pool :

```yaml
framework:
    cache:
        # ...
        pools:
            cache.tudor_estock:
                public: true
```

### Configure tudor-estock-client-bundle

In `config/packages/`, create a `idci_tudor_estock_client.yaml` file :

```yaml
idci_tudor_estock_client:
    guzzle_http_client_service_alias: 'eight_points_guzzle.client.tudor_estock_api'
    cache_pool_service_alias: 'cache.tudor_estock'
    client_id: '%env(string:IDCI_TUDOR_ESTOCK_CLIENT_ID)%'
    client_secret: '%env(string:IDCI_TUDOR_ESTOCK_CLIENT_SECRET)%'
    scope: '%env(string:IDCI_TUDOR_ESTOCK_SCOPE)%'
    issuer: '%env(string:IDCI_TUDOR_ESTOCK_ISSUER)%'
```

Required parameters:
* **guzzle_http_client_service_alias** : The guzzle HTTP client alias
* **client_id** : The oAuth client ID
* **client_secret** : The oAuth client secret
* **scope** : The oAuth client scope
* **issuer** : The oAuth token issuer


Then, add these environment variable in your `.env` file :

```
###> idci/tudor-estock-client-bundle ###
IDCI_TUDOR_ESTOCK_CLIENT_ID=
IDCI_TUDOR_ESTOCK_CLIENT_SECRET=
IDCI_TUDOR_ESTOCK_SCOPE=
IDCI_TUDOR_ESTOCK_ISSUER=
###< idci/tudor-estock-client-bundle ###
```