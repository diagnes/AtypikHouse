<?php

namespace AtypikHouseBundle\Tests\Controller;

use AtypikHouseBundle\Controller\ReservationAdminController;
use AtypikHouseBundle\Entity\Reservation;
use AtypikHouseBundle\Enum\ReservationStateEnum;
use AtypikHouseBundle\Service\ReservationManager;
use Doctrine\ORM\EntityManager;
use HousingBundle\Entity\Housing;
use PaymentBundle\Enum\MoneyMovementStateEnum;
use PaymentBundle\Enum\PaymentStateEnum;
use Psr\Container\ContainerInterface;
use ToolsBundle\Test\CustomTestCase;
use UserBundle\Entity\User;

/**
 * Class ReservationAdminControllerTest
 */
class ReservationAdminControllerTest extends CustomTestCase
{
    /**
     * TEST FONCTIONNNEL
     **/

    /**
     * @var Reservation $reservation
     */
    private static $reservation;

    /**
     * @var ReservationManager $reservationManager
     */
    private $reservationManager;

    /**
     * @var ContainerInterface $container
     */
    protected $container;


    /**
     * Set all information before all test
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $em = static::createClient()->getContainer()->get('doctrine.orm.entity_manager');
        fwrite(STDERR, "Start AdminReservation TEST \r\n");
        fwrite(STDERR, "Load new Data For reservation Test...\r\n");
        self::runCommand('doctrine:fixtures:load --append --fixtures=src/AtypikHouseBundle/DataFixtures/ORM/LoadReservationData.php');
        fwrite(STDERR, "Data Load Success  \r\n");
        static::$reservation = $em->getRepository(Reservation::class)->findOneBy([], ['id' => 'DESC']);
    }

    /**
     * Set all information before test start
     *
     * @return void
     */
    public function setUp()
    {

        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
        $this->em = $this->container->get('doctrine.orm.entity_manager');
        parent::setUp();
    }

    /**
     * Test if the action all send good response
     *
     * @see ReservationAdminController::allAction()
     *
     * @return void
     */
    public function testAllAction()
    {
        fwrite(STDERR, "Test ReservationAdminController::All \r\n");
        $client = static::createClient();
        $url = $client->getContainer()->get('router')->generate('api_reservation_admin_all');
        $client->request('GET', $url);
        $response = $client->getResponse();
        $this->assertEquals(302, $response->getStatusCode(), sprintf('Unexpected HTTP status code for GET %s', $url));
        fwrite(STDERR, "[DONE]:Get all reservation as anonymous impossible\r\n");

        $client = $this->logInAsClient();
        $url = $client->getContainer()->get('router')->generate('api_reservation_admin_all');
        $client->request('GET', $url);
        $response = $client->getResponse();
        $this->assertEquals(403, $response->getStatusCode(), sprintf('Unexpected HTTP status code for GET %s', $url));
        fwrite(STDERR, "[DONE]:Get all reservation as client impossible\r\n");

        $client = $this->logInAsAdmin();
        $url = $client->getContainer()->get('router')->generate('api_reservation_admin_all');
        $client->request('GET', $url);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode(), sprintf('Unexpected HTTP status code for GET %s', $url));
        fwrite(STDERR, "[DONE]:Get all reservation as admin\r\n");

        $content = json_decode($response->getContent(), true);
        $this->assertInternalType('array', $content);

        $this->assertArrayHasKey('reservations', $content, 'Response has no key call reservations');
        $this->assertArrayHasKey('data', $content['reservations'], 'Response reservation has no key call data');

        $last = \count($content['reservations']['data']) - 1;
        $reservation = $content['reservations']['data'][$last];
        $this->assertArrayHasKey('id', $reservation, 'Data Reservation is supposed to have Id');
        $this->assertArrayHasKey('user', $reservation, 'Data Reservation is supposed to have User');
        $this->assertArrayHasKey('state', $reservation, 'Data Reservation is supposed to have State');
        $this->assertArrayHasKey('housing', $reservation, 'Data Reservation is supposed to have Housing');
        fwrite(STDERR, "[DONE]: Content data are reservation with Id, User, state and Housing\r\n");
        fwrite(STDERR, "Test ReservationAdminController::All Success \r\n");
    }

    /**
     * Test if the action validate send good response
     *
     * @see ReservationAdminController::validateAction()
     *
     * @return void
     */
    public function testAdminValidateAction()
    {
        fwrite(STDERR, "Test ReservationAdminController::Validate \r\n");
        $client = static::createClient();
        $url = $client->getContainer()->get('router')->generate('api_reservation_admin_validate', ['id' => static::$reservation->getId()]);
        $client->request('GET', $url);
        $response = $client->getResponse();

        $em = $client->getContainer()->get('doctrine.orm.db_dev_entity_manager');
        $reservation = $em->getRepository(Reservation::class)->findOneBy(['id'=>static::$reservation->getId()]);
        $this->assertEquals(ReservationStateEnum::VALIDATED, $reservation->getState(), 'Unexpected State for reservation... Expected validated get ' .$reservation->getState());

        $this->assertEquals(201, $response->getStatusCode(), "Unexpected HTTP status code for GET $url");
        fwrite(STDERR, "Test ReservationAdminController::Validate Success \r\n");
    }

    /**
     * Test if the action refuse send good response
     *
     * @see ReservationAdminController::refusedAction()
     *
     * @return void
     */
    public function testAdminRefusedAction()
    {
        fwrite(STDERR, "Test ReservationAdminController::Refused \r\n");
        $client = static::createClient();
        $url = $client->getContainer()->get('router')->generate('api_reservation_admin_refused', ['id' => static::$reservation->getId()]);
        $client->request('GET', $url);

        $response = $client->getResponse();
        $em = $client->getContainer()->get('doctrine.orm.db_dev_entity_manager');
        $reservation = $em->getRepository(Reservation::class)->findOneBy(['id'=>static::$reservation->getId()]);
        $this->assertEquals(ReservationStateEnum::REFUSED, $reservation->getState(), 'Unexpected State for reservation... Expected refused get ' .$reservation->getState());

        $this->assertEquals(201, $response->getStatusCode(), "Unexpected HTTP status code for GET $url");
        fwrite(STDERR, "Test ReservationAdminController::Refused Success \r\n");
    }

    /**
     * Test if the action refuse send good response
     *
     * @see ReservationAdminController::deleteAction()
     *
     * @return void
     */
    public function testAdminDeleteAction()
    {
        fwrite(STDERR, "Test ReservationAdminController::Delete \r\n");
        $client = static::createClient();
        $url = $client->getContainer()->get('router')->generate('api_reservation_admin_deleted', ['id' => static::$reservation->getId()]);
        $client->request('GET', $url);

        $response = $client->getResponse();
        $em = $client->getContainer()->get('doctrine.orm.db_dev_entity_manager');
        $reservation = $em->getRepository('AtypikHouseBundle:Reservation')->findOneBy(['id'=>static::$reservation->getId()]);
        $this->assertEquals(ReservationStateEnum::CANCELED, $reservation->getState(), 'Unexpected State for reservation... Expected canceled get ' .$reservation->getState());
        $this->assertNotNull($reservation->getDeletedAt(), 'Deleted_at is not supposed to be null after delete but got null');

        $this->assertEquals(201, $response->getStatusCode(), "Unexpected HTTP status code for GET $url");
        fwrite(STDERR, "Test ReservationAdminController::Delete Success \r\n");
    }

    /**
     * Test if the action refuse send good response
     *
     * @see ReservationAdminController::undeleteAction()
     *
     * @return void
     */
    public function testAdminUndeletAction()
    {
        fwrite(STDERR, "Test ReservationAdminController::Undelete \r\n");
        $client = static::createClient();
        $url = $client->getContainer()->get('router')->generate('api_reservation_admin_undeleted', ['id' => static::$reservation->getId()]);
        $client->request('GET', $url);

        $response = $client->getResponse();
        $em = $client->getContainer()->get('doctrine.orm.db_dev_entity_manager');
        $reservation = $em->getRepository(Reservation::class)->findOneBy(['id'=>static::$reservation->getId()]);
        $this->assertEquals(ReservationStateEnum::CREATED, $reservation->getState(), 'Unexpected State for reservation... Expected created get ' .$reservation->getState());
        $this->assertEquals(null, $reservation->getDeletedAt(), 'Deleted_at is supposed to be null after undelete');

        $this->assertEquals(201, $response->getStatusCode(), "Unexpected HTTP status code for GET $url");
        fwrite(STDERR, "Test ReservationAdminController::Undelete Success \r\n");
    }

    /**
     * TEST UNITAIRE
     **/

    /**
     * Test if the service method give right reservation
     *
     * @see ReservationManager::getReservationEntity()
     *
     * @return void
     */
    public function testGetOneReservationService()
    {
        fwrite(STDERR, "Test Unitaire ReservationManager::getReservationEntity \r\n");
        $reservation = $this->reservationManager->getReservationEntity(static::$reservation->getId());
        $this->assertSame(new Reservation(), $reservation, sprintf('The result expected should be an instance of %s got %s instead', Reservation::class, sprintf('The result expected should be an instance of %s got %s instead', Reservation::class, \get_class($reservation))));
        fwrite(STDERR, "Test Unitaire ReservationManager::getReservationEntity Success \r\n");
    }

    /**
     * Test if the service method give right reservation
     *
     * @see ReservationManager::getAllReservationEntity()
     *
     * @return void
     */
    public function testGetAllReservationService()
    {
        fwrite(STDERR, "Test Unitaire ReservationManager::getReservationEntity \r\n");
        $reservations = $this->reservationManager->getAllReservationEntity();
        foreach ($reservations as $reservation) {
            $this->assertSame(new Reservation(), $reservation, sprintf('The result expected should be an instance of %s got %s instead', Reservation::class, sprintf('The result expected should be an instance of %s got %s instead', Reservation::class, \get_class($reservation))));
        }
        fwrite(STDERR, "Test Unitaire ReservationManager::getReservationEntity Success \r\n");
    }

    /**
     * Test if the service method give right reservation
     *
     * @see ReservationManager::getAllReservationEntity()
     *
     * @return void
     */
    public function testPaidReservation()
    {
        fwrite(STDERR, "Test Unitaire ReservationManager::getReservationEntity \r\n");
        $this->reservationManager->paidReservation(static::$reservation);
        $client = static::createClient();

        $fees = $client->getKernel()->getContainer()->getParameter('atypikhouse_global_fees');
        $price = static::$reservation->getHousing()->getPrice() * static::$reservation->getReservationInfos()->getInterval();
        $finalPrice = $price + ($price * ( $fees / 100));

        $paymentInfo = static::$reservation->getPaymentInfo();
        $moneyMovement = $paymentInfo->getMoneyMovements();

        $this->assertEquals($finalPrice, $paymentInfo->getPrice(), 'Different price');
        $this->assertEquals(PaymentStateEnum::CREATED, $moneyMovement->getState(), sprintf('Expected created for state MoneyMovement got %s instead', $moneyMovement->getState()));
        $this->assertEquals(MoneyMovementStateEnum::PAYIN, $moneyMovement->getAction(), sprintf('Expected payin for action MoneyMovement got %s instead', $moneyMovement->getAction()));

        fwrite(STDERR, "Test Unitaire ReservationManager::getReservationEntity Success \r\n");
    }
}
