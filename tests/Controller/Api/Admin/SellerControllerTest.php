<?php

namespace App\Tests\Controller\Api\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test Admin API endpoints - requires authentication
 */
final class SellerControllerTest extends WebTestCase
{
    public function testListSellersRequiresAuthentication(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/admin/sellers');

        // Sans authentification, on attend une erreur 401
        self::assertResponseStatusCodeSame(401);
    }
}
