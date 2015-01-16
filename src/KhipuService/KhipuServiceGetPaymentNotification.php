<?php

/**
 * (c) Emilio Davis <emilio.davis@khipu.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once 'KhipuService.php';

/**
 * Servicio GetPaymentNotification extiende de KhipuService
 *
 * Este servicio consulta una notificacion de pago
 */
class KhipuServiceGetPaymentNotification extends KhipuService {
  /**
   * Iniciamos el servicio
   */
  public function __construct($receiver_id, $secret) {
    parent::__construct($receiver_id, $secret);
    // Asignamos la url del servicio
    $this->apiUrl = Khipu::getUrlService('GetPaymentNotification');
    $this->data = array(
	          'notification_token'  => '',
		      );
  }

  /**
   * Método que consulta por la notificacion de pago
   */
  public function consult() {
    $string_data = $this->dataToString();

    $data_to_send = array(
      'hash' => $this->doHash($string_data),
      'receiver_id' => $this->receiver_id,
      'notification_token' => $this->data['notification_token'],
    );
    $data_to_send['agent'] = $this->agent;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_to_send);
    $output = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);

    return $output;
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

  protected function dataToString() {
    $string = '';
    $string .= 'receiver_id='         . $this->receiver_id;
    $string .= '&notification_token=' . $this->data['notification_token'];
    return trim($string);
  }
}
