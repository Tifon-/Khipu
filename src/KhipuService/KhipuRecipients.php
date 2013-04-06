<?php

/**
 * @file
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
    if (count($this->recipients) == LIMIT_RECIPIENTS) {
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
}