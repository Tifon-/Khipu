<?php

/**
 * (c) Nicolas Moncada <nicolas.moncada@tifon.cl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once 'KhipuService.php';
require_once 'KhipuRecipients.php';

/**
 * Servicio CreateEmail que extiende de KhipuService.
 *
 * Este servicio permite generar cobros a un maximo de 50 destinatarios.
 */
class KhipuServiceCreateEmail extends KhipuService {

  /**
   * Objeto que se encarga de mantener los destinatarios.
   *
   * @var KhipuRecipients
   */
  private $recipients;

  /**
   * Son los detinatarios en JSON
   *
   * @var string
   */
  private $recipients_json;

  /**
   * Iniciamos el servicio identificando al cobrador.
   */
  function __construct($receiver_id, $secret){
    parent::__construct($receiver_id, $secret);
    // Cargamos el objeto KhipuRecipientes para adjuntar destinatarios.
    $this->recipients = new KhipuRecipients();
    // Iniciamos la variable apiUrl con la url del servicio.
    $this->apiUrl = Khipu::getUrlService('CreateEmail');
    // Iniciamos el arreglo $data con los valores que requiere el servicio.
    $this->data = array(
      'receiver_id' => $receiver_id,
      'subject' => '',
      'body' => '',
      'transaction_id' => '',
      'custom' => '',
      'notify_url' => '',
      'return_url' => '',
      'cancel_url' => '',
      'pay_directly' => 'true',
      'send_emails' => 'true',
      'expires_date' => '',
      'picture_url' => '',
    );
  }

  /**
   * Este metodo se encarga de adjuntar un destinatario al objeto.
   *
   * @param string $name
   *   Nombre del pagador.
   * @param string $email
   *   Correo electrónico del pagador.
   * @param int $amount
   *   Monto que pagará el pagador.
   */
  public function addRecipient($name, $email, $amount) {
    $this->recipients->addRecipient($name, $email, $amount);
    return $this;
  }


  /**
   * Método que retorna los destinatarios
   */
  public function getRecipients() {
    return $this->recipients->getRecipients();
  }

  /**
   * Limpa los destinatarios.
   */
  public function cleanRecipients() {
    $this->recipients->cleanRecipients();
    return $this;
  }

  /**
   * Metodo que envia la solicitud a Khipu para generar los cobros.
   */
  public function send() {
    // Pasamos los destinatarios al formato JSON
    $this->recipientsToJson();
    // Generamos el string desde los datos.
    $string_data = $this->dataToString();
    // iniciamos un arreglo con los datos a enviar a la solicitud
    // y le atachamos el Hash de string_data y los detinatarios en JSON
    $data_to_send = array(
      'hash' => $this->doHash($string_data),
      'destinataries' => $this->recipients_json,
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
      return $this->prepareOutput($output);
    }
    else {
      return FALSE;
    }

  }


  /**
   * Método que prepara la respuesta json a un arreglo.
   */
  private function prepareOutput($json) {
    $decode = json_decode($json);
    $payment = array(
      'bill_id' => $decode->id,
      'list' => array(),
    );

    foreach ($decode->payments as $data) {
      $payment['list'][$id] = array(
        'link'        => $data->url,
        'mail'        => $data->email,
        'payment_id'  => $data->id,
      );
    }

    return $payment;
  }

  /**
   * Método que asigna a formato JSON los detinatarios
   */
  private function recipientsToJson() {
    $this->recipients_json = $this->recipients->getJson();
  }

  protected function dataToString() {
    $string = '';
    $string .= 'receiver_id='     . $this->data['receiver_id'];
    $string .= '&subject='        . $this->data['subject'];
    $string .= '&body='           . $this->data['body'];
    $string .= '&destinataries='  . $this->recipients_json;
    $string .= '&pay_directly='   . $this->data['pay_directly'];
    $string .= '&send_emails='    . $this->data['send_emails'];
    $string .= '&expires_date='   . $this->data['expires_date'];
    $string .= '&transaction_id=' . $this->data['transaction_id'];
    $string .= '&custom='         . $this->data['custom'];
    $string .= '&notify_url='     . $this->data['notify_url'];
    $string .= '&return_url='     . $this->data['return_url'];
    $string .= '&cancel_url='     . $this->data['cancel_url'];
    $string .= '&picture_url='    . $this->data['picture_url'];
    return trim($string);
  }
}
