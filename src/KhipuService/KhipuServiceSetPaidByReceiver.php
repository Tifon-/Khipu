<?php

/**
 * (c) Nicolas Moncada <nicolas.moncada@tifon.cl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once 'KhipuService.php';

/**
 * Servicio KhipuServiceSetPayedByReceiver extiende de KhipuService
 *
 * Este servicio marca un pago como pagado
 */
class KhipuServiceSetPayedByReceiver extends KhipuService {
  /**
   * Iniciamos el servicio
   */
  public function __construct($receiver_id, $secret) {
    parent::__construct($receiver_id, $secret);
    // Asignamos la url del servicio
    $this->apiUrl = Khipu::getUrlService('SetPayedByReceiver');
    $this->data = array(
      'payment_id'     => '',
    );
  }

  /**
   * Método que envia la solicitud
   *
   * @return bool
   */
  public function set() {
    $string_data = $this->dataToString();

    $data_to_send = array(
      'hash' => $this->doHash($string_data),
      'receiver_id' => $this->receiver_id,
      'payment_id' => $this->data['payment_id'],
    );
    $data_to_send['agent'] = $this->agent;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_to_send);

    $this->message = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);

    return $info['http_code'] == 200;
  }

  protected function dataToString() {
    $string = '';
    $string .= 'receiver_id='     . $this->receiver_id;
    $string .= '&payment_id='     . $this->data['payment_id'];
    return trim($string);
  }
}
