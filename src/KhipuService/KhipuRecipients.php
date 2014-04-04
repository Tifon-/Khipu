<?php

/**
 * (c) Nicolas Moncada <nicolas.moncada@tifon.cl>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Clase para registrar los destinatarios.
 */ 
class KhipuRecipients {
  /**
   * Arreglo de destinatarios
   * 
   * @var array
   */
  private $recipients = array();
  
  /**
   * Limite de destinatarios del servicio.
   */
  const LIMIT_RECIPIENTS = 50;
  
  /**
   * Metodo que asigna a la variable recipientes un nuevo destinatario.
   */
  public function addRecipient($name, $email, $amount) {
    if (count($this->recipients) == self::LIMIT_RECIPIENTS) {
      // El servicio tiene un limite
      return;
    }
    $this->recipients[] = array(
      'name' => $name,
      'email' => $email,
      'amount' => $amount,
    );
  }
  
  /**
   * Método que pasa los detinatarios a JSON.
   */
  public function getJson() {
    return json_encode($this->recipients);
  }
  
  /**
   * Método que limpia los destinatarios.
   */
  public function cleanRecipients() {
    $this->recipients = array();
  }
  
  
  public function getRecipients() {
    return $this->recipients;
  }
}
