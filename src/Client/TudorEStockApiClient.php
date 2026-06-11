<?php

namespace IDCI\Bundle\TudorEStockClientBundle\Client;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use IDCI\Bundle\TudorEStockClientBundle\Model\Health;
use IDCI\Bundle\TudorEStockClientBundle\Model\PointOfSales;
use IDCI\Bundle\TudorEStockClientBundle\Model\Stock;
use IDCI\Bundle\TudorEStockClientBundle\Model\StocksBatchResponse;
use IDCI\Bundle\TudorEStockClientBundle\Model\StocksResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\SerializerInterface;

class TudorEStockApiClient
{
    private LoggerInterface $logger;
    private SerializerInterface $serializer;
    private ?AdapterInterface $cache;
    private ?ClientInterface $httpClient = null;
    private string $clientId;
    private string $clientSecret;
    private string $scope;
    private string $issuer;

    public function __construct(
        LoggerInterface $logger,
        SerializerInterface $serializer,
        ?AdapterInterface $cache,
        string $clientId,
        string $clientSecret,
        string $scope,
        string $issuer,
    ) {
        $this->logger = $logger;
        $this->serializer = $serializer;
        $this->cache = $cache;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->scope = $scope;
        $this->issuer = $issuer;
    }

    public function setHttpClient(ClientInterface $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    public function setCache(AdapterInterface $cache): void
    {
        $this->cache = $cache;
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function getAccessTokenUrl(): string
    {
        return sprintf('https://login.rolex.com/oauth2/%s/v1/token', $this->issuer);
    }

    private function getAccessTokenCacheKey(): string
    {
        return 'idci_tudor_estock_client.access_token';
    }

    public function getAccessTokenResponse(): ?Response
    {
        $response = null;

        try {
            $response = $this->httpClient->request('POST', $this->getAccessTokenUrl(), [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'scope' => $this->scope,
                ],
            ]);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return $response;
    }

    public function getAccessToken(): array
    {
        if ($this->cache->hasItem($this->getAccessTokenCacheKey())) {
            $accessToken = $this->cache->getItem($this->getAccessTokenCacheKey())->get();

            return json_decode($accessToken, true);
        }

        $response = $this->getAccessTokenResponse();

        if (null === $response) {
            throw new \Exception('Could not retrieve access token');
        }

        $accessToken = json_decode((string) $response->getBody(), true);

        $item = $this->cache->getItem($this->getAccessTokenCacheKey());
        $item->set((string) $response->getBody());
        $item->expiresAfter($accessToken['expires_in']);
        $this->cache->save($item);

        return $accessToken;
    }

    public function getStocks(int $page, int $size): ?StocksResponse
    {
        try {
            $response = $this->httpClient->request('GET', 'v1/stocks', [
                'headers' => [
                    'Authorization' => sprintf('Bearer %s', $this->getAccessToken()['access_token']),
                    'Content-Type' => 'application/json',
                ],
                'query' => [
                    'page' => $page,
                    'size' => $size,
                ],
            ]);

            return $this->serializer->deserialize((string) $response->getBody(), StocksResponse::class, 'json');
        } catch (RequestException $e) {
            $this->logRequestException($e);
        }

        return null;
    }

    public function updateStock(array $stock): ?Stock
    {
        $resolver = (new OptionsResolver())
            ->setRequired('mc')->setAllowedTypes('mc', ['string'])
            ->setRequired('country')->setAllowedTypes('country', ['string'])
            ->setRequired('value')->setAllowedTypes('value', ['int'])
            ->setRequired('defaultUrl')->setAllowedTypes('defaultUrl', ['string'])
            ->setDefined('localizedUrls')->setAllowedTypes('localizedUrls', ['array'])
            ->setRequired('storePickupAvailable')->setAllowedTypes('storePickupAvailable', ['bool'])
            ->setRequired('onlinePurchaseEnabled')->setAllowedTypes('onlinePurchaseEnabled', ['bool'])
            ->setDefined('homeDeliveryTiming')->setAllowedTypes('homeDeliveryTiming', ['int', 'null'])
            ->setDefined('storePickupTiming')->setAllowedTypes('storePickupTiming', ['int', 'null'])
        ;

        $body = $resolver->resolve($stock);

        try {
            $response = $this->httpClient->request('POST', 'v1/stocks', [
                'headers' => [
                    'Authorization' => sprintf('Bearer %s', $this->getAccessToken()['access_token']),
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode($body),
            ]);

            return $this->serializer->deserialize((string) $response->getBody(), Stock::class, 'json');
        } catch (RequestException $e) {
            $this->logRequestException($e, $body);
        }

        return null;
    }

    public function updateStocks(array $stocks): ?array
    {
        $resolver = (new OptionsResolver())
            ->setRequired('mc')->setAllowedTypes('mc', ['string'])
            ->setRequired('country')->setAllowedTypes('country', ['string'])
            ->setRequired('value')->setAllowedTypes('value', ['int'])
            ->setRequired('defaultUrl')->setAllowedTypes('defaultUrl', ['string'])
            ->setDefined('localizedUrls')->setAllowedTypes('localizedUrls', ['array'])
            ->setRequired('storePickupAvailable')->setAllowedTypes('storePickupAvailable', ['bool'])
            ->setRequired('onlinePurchaseEnabled')->setAllowedTypes('onlinePurchaseEnabled', ['bool'])
            ->setDefined('homeDeliveryTiming')->setAllowedTypes('homeDeliveryTiming', ['int'])
            ->setDefined('storePickupTiming')->setAllowedTypes('storePickupTiming', ['int'])
        ;

        $body = [];
        foreach ($stocks as $stock) {
            $body[] = $resolver->resolve($stock);
        }

        try {
            $response = $this->httpClient->request('POST', 'v1/stocks/batch', [
                'headers' => [
                    'Authorization' => sprintf('Bearer %s', $this->getAccessToken()['access_token']),
                    'Content-Type' => 'application/json',
                ],
                'body' => $body,
            ]);

            return $this->serializer->deserialize((string) $response->getBody(), sprintf('%s[]', StocksBatchResponse::class), 'json');
        } catch (RequestException $e) {
            $this->logRequestException($e, $body);
        }

        return null;
    }

    public function getPointOfSales(): ?array
    {
        try {
            $response = $this->httpClient->request('GET', 'v1/point-of-sales', [
                'headers' => [
                    'Authorization' => sprintf('Bearer %s', $this->getAccessToken()['access_token']),
                    'Content-Type' => 'application/json',
                ],
            ]);

            return $this->serializer->deserialize((string) $response->getBody(), sprintf('%s[]', PointOfSales::class), 'json');
        } catch (RequestException $e) {
            $this->logRequestException($e);
        }

        return null;
    }

    public function getHealth(): ?Health
    {
        try {
            $response = $this->httpClient->request('GET', 'health', [
                'headers' => [
                    'Authorization' => sprintf('Bearer %s', $this->getAccessToken()['access_token']),
                    'Content-Type' => 'application/json',
                ],
            ]);

            return $this->serializer->deserialize((string) $response->getBody(), Health::class, 'json');
        } catch (RequestException $e) {
            dd($e);
            $this->logRequestException($e);
        }

        return null;
    }

    private function logRequestException(RequestException $e, array $options = []): void
    {
        $this->logger->error(
            sprintf(
                'method : %s, url : %s, status : %s, data : %s, message : %s',
                $e->getRequest()->getMethod(),
                $e->getRequest()->getUri(),
                null != $e->getResponse() ? (string) $e->getResponse()->getStatusCode() : null,
                json_encode($options),
                null != $e->getResponse() ? (string) $e->getResponse()->getBody() : $e->getMessage()
            )
        );
    }
}
