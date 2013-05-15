<?php

/**
 * (c) Nicolas Moncada <nicolas.moncada@tifon.cl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once 'KhipuService.php';

/**
 * Servicio VerifyPaymentNotification extiende de KhipuService
 *
 * Esta clase verifica la notificacion enviada por Khipu por un pago
 */
class KhipuServiceVerifyPaymentNotification extends KhipuService {

  /**
   * Iniciamos el servicio
   */
  public function __construct() {
    // Asignamos la url del servicio
    $this->apiUrl = Khipu::getUrlService('VerifyPaymentNotification');
    // Iniciamos los datos requeridos por le servicio
    $this->data = array(
      'api_version' => '',
      'receiver_id' => '',
      'notification_id' => '',
      'subject' => '',
      'amount' => '',
      'currency' => '',
      'transaction_id' => '',
      'payer_email' => '',
      'custom' => '',
      'notification_signature' => '',
    );
  }

  /**
   * Esta funcion es para asignar los valores recibidos por POST.
   *
   * Puede usarse en reemplazo del metodo setParameter().
   */
  public function setDataFromPost() {
    // Recorremos el arreglo $data
    foreach ($this->data as $key => $value) {
      // Si existe la llave en $_POST entonces asignamos su valor
      // a $data
      if (isset($_POST[$key])) {
        $this->data[$key] = $_POST[$key];
      }
    }
  }

  /**
   * Método que envía a Khipu los datos para verificar si fueron enviados
   * por ellos mismos.
   */
  public function verify() {
    // Pasamos los datos a string
    $data = $this->dataToString();
    // Iniciamos CURL
    $ch = curl_init($this->apiUrl);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $response = curl_exec($ch);
    // @TODO: que hacer en caso de error del servidor?
    $info = curl_getinfo($ch);
    curl_close($ch);
    return array(
      'response' => $response,
      'info' => $info,
    );
  }

  protected function dataToString() {
    $string = '';
    $string .= 'api_version='             . urlencode($this->data['api_version']);
    $string .= '&receiver_id='            . urlencode($this->data['receiver_id']);
    $string .= '&notification_id='        . urlencode($this->data['notification_id']);
    $string .= '&subject='                . urlencode($this->data['subject']);
    $string .= '&amount='                 . urlencode($this->data['amount']);
    $string .= '&currency='               . urlencode($this->data['currency']);
    $string .= '&transaction_id='         . urlencode($this->data['transaction_id']);
    $string .= '&payer_email='            . urlencode($this->data['payer_email']);
    $string .= '&custom='                 . urlencode($this->data['custom']);
    $string .= '&notification_signature=' . urlencode($this->data['notification_signature']);
    return $string;
  }
}
