<?php

namespace App\Bundle\BackOfficeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImportControllerTest extends WebTestCase
{
    public function testUpdate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/import/data');
    }

}
