<?php

/**
 * (c) Nicolas Moncada <nicolas.moncada@tifon.cl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once 'KhipuService.php';

/**
 * Servicio SetBillExpired extiende de KhipuService
 *
 * Este servicio marca un cobro como expirado
 */
class KhipuServiceSetBillExpired extends KhipuService {
  /**
   * Iniciamos el servicio
   */
  public function __construct($receiver_id, $secret) {
    parent::__construct($receiver_id, $secret);
    // Asignamos la url del servicio
    $this->apiUrl = Khipu::getUrlService('SetBillExpired');
    $this->data = array(
      'bill_id'     => '',
      'text'        => '',
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
      'bill_id' => $this->data['bill_id'],
      'text' => $this->data['text'],
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
    $string .= '&bill_id='        . $this->data['bill_id'];
    $string .= '&text='           . $this->data['text'];
    return trim($string);
  }
}
