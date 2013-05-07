<?php

require_once '../src/Khipu.php';

require_once 'settingsTest.php';

class KhipuTest extends PHPUnit_Framework_TestCase {

  private $services_name;

  public function setUp() {
    $this->services_name = Khipu::getAllServicesName();
  }

  public function testAuthenticate() {

    $khipu = new KhipuToTest();
    $khipu->authenticate(KHIPU_TEST_RECEIVER_ID, KHIPU_TEST_SECRET);
    $this->assertEquals($khipu->getReceiverId(), KHIPU_TEST_RECEIVER_ID,
      'Se espera que el id del cobrador se haya asignado');

    $this->assertEquals($khipu->getSecret(), KHIPU_TEST_SECRET,
      'Se espera que la llave del cobrador se haya asignado');
  }

  public function testLoadServiceException() {
    $khipu = new Khipu();
    // Al cargar un servicio que no existe, se espera un Exception de retorno
    $this->assertInstanceOf('Exception', $this->loadServiceKhipu($khipu, 'ServiceNotExist'));

    // Al cargar un servicio que requiere autentificacion pero sin darlo, se
    // espera un Exception de retorno
    $this->assertInstanceOf('Exception', $this->loadServiceKhipu($khipu, 'ServiceNotExist'));
  }

  public function testLoadServiceCreateEmailException() {
    $khipu = new Khipu();
    $this->assertInstanceOf('Exception', $this->loadServiceKhipu($khipu, 'CreateEmail'),
      'Se espera que haya error ya que se requiere autentificación');
  }

  public function testLoadServiceCreateEmailSuccess() {
    $khipu = new Khipu();
    $khipu->authenticate(KHIPU_TEST_RECEIVER_ID, KHIPU_TEST_SECRET);
    $this->assertInstanceOf('KhipuService', $this->loadServiceKhipu($khipu, 'CreateEmail'));
  }

  public function testLoadServiceCreatePaymentPageException() {
    $khipu = new Khipu();
    $this->assertInstanceOf('Exception', $this->loadServiceKhipu($khipu, 'CreatePaymentPage'),
      'Se espera que haya error ya que se requiere autentificación');
  }

  public function testLoadServiceCreatePaymentPageSuccess() {
    $khipu = new Khipu();
    $khipu->authenticate(KHIPU_TEST_RECEIVER_ID, KHIPU_TEST_SECRET);
    $this->assertInstanceOf('KhipuService', $this->loadServiceKhipu($khipu, 'CreatePaymentPage'));
  }


  public function testGetUrlService() {
    $this->assertTrue(Khipu::getUrlService('CreateEmail') !== FALSE,
      'Se espera que retorne una URL');
  }

  public function testExistsServices() {
    $khipu = new Khipu();
    $khipu->authenticate(KHIPU_TEST_RECEIVER_ID, KHIPU_TEST_SECRET);
    $exists = TRUE;
    $fail = '';
    foreach ($this->services_name as $service_name => $need_authenticate) {
      if (!$this->loadServiceKhipu($khipu, $service_name) instanceof KhipuService) {
        $exists = FALSE;
        $fail = $service_name;
        break;
      }
    }
    $this->assertTrue($exists, 'Deben existir todos los servicios. No existe ' . $fail);
  }

  /**
   * Método para cargar un servicio y capturar el Exception en caso de error.
   */
  private function loadServiceKhipu(Khipu $khipu, $service) {
    try {
      return $khipu->loadService($service);
    }
    catch(Exception $exp) {
      return $exp;
    }
  }
}



class KhipuToTest extends Khipu {
  public function getReceiverId() {
    return $this->receiver_id;
  }
  public function getSecret() {
    return $this->secret;
  }
}
