<?php

namespace App\US\Infrastructure\Client\Notification;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class NotificationClient
{
    private string $host = 'http(s)://locahost';
    private string $endpoint = '/notification';
    private string $token = '';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly LoggerInterface $logger,
        /** @phpstan-ignore-next-line */
        private readonly ContainerBagInterface $params
    ) {
        $this->validaton($params);
    }

    public function sendEmail(string $email, string $subject, string $context): ResponseInterface
    {
        $request = new NotificationRequest(
            $this->token,
            'email',
            [
                'email' => $email,
                'subject' => $subject,
                'context' => $context,
            ]
        );

        return $this->request($request);
    }

    /**
     * @return void
     */
    private function validaton(ContainerBagInterface $params)
    {
        if ($params->has('nfs_host')) {
            $this->host = $params->get('nfs_host');
        }
        if ($params->has('nfs_token')) {
            $this->token = $params->get('nfs_token');
        }
    }

    private function request(NotificationRequest $notificationRequest): ResponseInterface
    {
        $this->RequestLog($notificationRequest);
        try {
            $response = $this->httpClient->request(
                'POST',
                $this->getURI(),
                [
                    'headers' => [
                        'Authorization' => $this->token,
                    ],
                    'body' => $this->createBodyRequest($notificationRequest),
                ]
            );
            $this->ResponseLog($response);
        } catch (ClientException $e) {
            $this->logger->critical("
                Code: {$e->getCode()}
                Message: {$e->getMessage()}
                File: {$e->getFile()}
                Line: {$e->getLine()}
            ");

            return $e->getResponse();
            // @todo  zrobć powtarzanie, poprawić return data
        }

        return $response;
    }

    private function RequestLog(NotificationRequest $notificationRequest): void
    {
        $this->logger->info("
            type: {$notificationRequest->type}
            data: ".implode(',', $notificationRequest->data).'
        ');
    }

    private function ResponseLog(ResponseInterface $response): void
    {
        $this->logger->info("
            code: {$response->getStatusCode()} ,
            content: {$response->getContent()} ,
        ");
    }

    private function createBodyRequest(NotificationRequest $notificationRequest): string
    {
        $body = [
            'token' => $this->token,
            'type' => $notificationRequest->type,
        ];

        foreach ($notificationRequest->data as $key => $value) {
            $body[$key] = $value;
        }
        /* @phpstan-ignore-next-line */
        return json_encode($body);
    }

    private function getURI(): string
    {
        return $this->host.$this->endpoint;
    }
}
