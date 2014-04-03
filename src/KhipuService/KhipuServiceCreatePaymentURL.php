<?php

/**
 * (c) Nicolas Moncada <nicolas.moncada@tifon.cl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once 'KhipuService.php';

/**
 * Servicio CreatePaymentURL que extiende de KhipuService.
 *
 * Este servicio facilita la creación de un pago.
 */
class KhipuServiceCreatePaymentURL extends KhipuService {

  /**
   * Iniciamos el servicio
   */
  public function __construct($receiver_id, $secret) {
    parent::__construct($receiver_id, $secret);
    // Iniciamos la variable apiUrl con la url del servicio.
    $this->apiUrl = Khipu::getUrlService('CreatePaymentURL');
    // Iniciamos el arreglo $data con los valores que requiere el servicio.
    $this->data = array(
      'receiver_id' => $receiver_id,
      'subject' => '',
      'body' => '',
      'amount' => 0,
      'custom' => '',
      'notify_url' => '',
      'return_url' => '',
      'cancel_url' => '',
      'bank_id' => '',
      'expires_date' => '',
      'transaction_id' => '',
      'picture_url' => '',
      'payer_email' => '',
    );
  }


  /**
   * Metodo que solicita la generacion de la url
   */
  public function createUrl() {
    $string_data = $this->dataToString();
    $data_to_send = array(
      'hash' => $this->doHash($string_data),
    );
    // Adicionalmente adjuntamos el resto de los valores iniciados en $data
    foreach ($this->data as $name => $value) {
      $data_to_send[$name] = $value;
    }
    $data_to_send['agent'] = $this->agent;

    // Iniciamos CURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_to_send);

    $output = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    if ($info['http_code'] == 200) {
      return $output;
    }
    else {
      return FALSE;
    }
  }

  protected function dataToString() {
    $string = '';
    $string .= 'receiver_id=' . $this->data['receiver_id'];
    $string .= '&subject=' . $this->data['subject'];
    $string .= '&body=' . $this->data['body'];
    $string .= '&amount=' . $this->data['amount'];
    $string .= '&payer_email=' . $this->data['payer_email'];
    $string .= '&bank_id=' . $this->data['bank_id'];
    $string .= '&expires_date=' . $this->data['expires_date'];
    $string .= '&transaction_id=' . $this->data['transaction_id'];
    $string .= '&custom=' . $this->data['custom'];
    $string .= '&notify_url=' . $this->data['notify_url'];
    $string .= '&return_url=' . $this->data['return_url'];
    $string .= '&cancel_url=' . $this->data['cancel_url'];
    $string .= '&picture_url=' . $this->data['picture_url'];
    return $string;
  }
}
