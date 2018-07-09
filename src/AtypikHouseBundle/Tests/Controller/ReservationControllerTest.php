<?php

namespace AtypikHouseBundle\Tests\Controller;

use AtypikHouseBundle\Controller\ReservationController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReservationControllerTest extends WebTestCase
{
    /**
     * Test if the action send good response
     *
     * @see ReservationController::newAction()
     *
     * @return void
     */
    public function testAllAction()
    {
        $client = static::createClient();
        $url = $client->getContainer()->get('router')->generate('api_reservation_new');
        $client->request('GET', $url);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode(), "Unexpected HTTP status code for GET $url");
    }
}
