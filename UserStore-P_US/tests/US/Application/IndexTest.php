<?php

namespace App\Tests\US\Application;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class IndexTest extends WebTestCase
{
    /**
     * @group Application
     * 
     * @return void
     */
    public function testMainPagSymfony(): void
    {
        $client = $this->createClient();
        $client->request(Request::METHOD_GET, '/');
        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString("Project [P_US] User Story", $response->getContent());
    }
}
