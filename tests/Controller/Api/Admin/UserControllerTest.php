<?php

namespace App\Tests\Controller\Api\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class UserControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/admin/user');

        self::assertResponseIsSuccessful();
    }
}
