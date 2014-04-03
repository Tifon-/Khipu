<?php

/**
 * (c) Nicolas Moncada <nicolas.moncada@tifon.cl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


/**
 * Clase abstracta que contiene las funciones que todas los servicios de Khipu
 * contarán. Es requisto que implementen la funcion dataToString(), la cual
 * prepara el arreglo para ser enviados a la solicitud del servicio.
 */
abstract class KhipuService {

  /**
   * Id del Cobrador
   *
   * @var string
   */
  protected $receiver_id;

  /**
   * Llave del Cobrador
   *
   * @var string
   */
  protected $secret;

  /**
   * Url del servicio
   *
   * @var string
   */
  protected $apiUrl;

  /**
   * Arreglo de los datos que se enviarán al servicio
   *
   * @var array
   */
  protected $data = array();

  /**
   * Mensaje en caso de error u otro evento
   */
  protected $message = '';

  /**
   * Por defecto iniciamos el servicio identificando al cobrador.
   */
  public function __construct($receiver_id, $secret) {
    $this->receiver_id = $receiver_id;
    $this->secret = $secret;
  }

  protected $agent;
  public function setAgent($agent) {
      $this->agent = $agent;
      return $this;
  }

  /**
   * Genera el Hash que requiere Khipu.
   *
   * @param string $string
   *   El string corresponde a los datos guardados en $data despues
   *   de aplicar el método dataToString().
   * @return string
   */
  protected function doHash($string) {
    return hash_hmac('sha256', $string, $this->secret);
  }

  /**
   * Metodo para adjuntar el valor a uno de los elementos que
   * contempla el arreglo $data. Esta funcion solo registrará los valores
   * que estan definidos en el arreglo.
   *
   * @param string $name
   *   Corresponde al nombre de la llave de algún elemento del arreglo $data
   * @param string $value
   *   Valor que se registrará en el elemento de la llave $name
   */
  public function setParameter($name, $value) {
    if (isset($this->data[$name])) {
      $this->data[$name] = $value;
    }
    return $this;
  }

  /**
   * Método para guardar, desde un arreglo, todos los elementos que debe
   * tener el arreglo $data.
   *
   * @param array $values
   *   Arreglo con los elementos a guardar en $data.
   */
  public function setParameters($values) {
    foreach ($values as $name => $value) {
      $this->setParameter($name, $value);
    }
  }

  /**
   * Método para capturar el mensaje.
   */
  public function getMessage() {
    return $this->message;
  }

  /**
   * Método que retorna un arreglo con los nombres de las llaves del arreglo
   * $data
   */
  public function getParametersNames() {
    $parameters = array_keys($this->data);
    return $parameters;
  }

  /**
   * Funcion que retorna la URL del servicio
   *
   * @return string
   *   Url del servicio.
   */
  public function getApiUrl() {
    return $this->apiUrl;
  }

  /**
   * Este método se encarga de pasar el arreglo $data a un string.
   * Cada servicio requiere que las variables esten en un orden en especifico
   * para ser aceptados en el servicio de Khipu.
   */
  abstract protected function dataToString();
}
