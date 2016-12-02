<?php

namespace AVBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EnvioEmailControllerControllerTest extends WebTestCase
{
    public function testEnviar()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/enviarEmail');
    }

}
