<?php

require_once '../src/Khipu.php';

require_once 'settingsTest.php';

class KhipuServiceCreateEmailTest extends PHPUnit_Framework_TestCase {

  private $service;

  public function setUp() {
    $khipu = new Khipu();
    $khipu->authenticate(KHIPU_TEST_RECEIVER_ID, KHIPU_TEST_SECRET);
    $this->service = $khipu->loadService('CreateEmail');
  }

  public function testRecipients() {
    $this->service->addRecipient('Client', 'email@email.com', 20000);

    $this->assertTrue(count($this->service->getRecipients()) == 1,
      'Se espera que haya solo un destinatario.');

  }

  public function testLimitRecipients() {
    for ($count = 0; $count < 55; $count++) {
      $this->service->addRecipient("Client $count", "email$count@email.com", 2000);
    }

    $this->assertTrue(count($this->service->getRecipients()) == 50,
      'Como maximo solo puede contar con 50 destinatarios');
  }

  public function testCleanRecipients() {
    $this->service->cleanRecipients();
    $this->assertTrue(count($this->service->getRecipients()) == 0,
      'Debe estar vacío la lista de destinatarios.');
  }

  public function testSend() {
    $this->service->addRecipient('Client', 'email@email.com', 20000);
    $json = $this->service->send();

    $this->assertTrue($json !== FALSE, 'Se espera un texto json.');
  }
}