# Khipu

Versión Biblioteca: 1.3

Versión API Khipu: 1.3

Biblioteca PHP para utilizar los servicios de Khipu.com

La documentación de Khipu.com se puede ver desde aquí: https://khipu.com/page/api

## Introducción

La API de Khipu cuenta con varios servicios, los cuales son:

* Ver estado de la cuenta Khipu
* Crear un botón de pago
* Crear un cobro por Mail
* Crear URL para un pago
* Expirar un cobro
* Consultar estado de un pago
* Marcar un pago como pagado
* Marcar un pago como rechazado
* Actualizar versión de la notificación
* Obtener listado de bancos

Para utilizar estos servicios se debe cargar el archivo Khipu.php


## Algunos Ejemplos
### 1) Crear Cobros y enviarlos por Mail

Para crear cobros, necesitamos identificar al cobrador y a los destinatarios.
A continuación un ejemplo

```php
<?php
  // ...
  require_once "Khipu.php";
  $khipu = new Khipu();
  // Para usar el servicio para crear cobros y enviarlos por Mail necesitamos
  // identificar al cobrador ya que es requerido para el servicio.
  $khipu->authenticate($receiver_id, $llave);

  // Luego cargamos el servicio.
  $khipu_service = $khipu->loadService('CreateEmail');

  // Preparamos los datos que queremos enviar
  $data = array(
    'subject' => 'Título del pago',
    'body' => 'Descripción del producto',
    'transaction_id' => '1',
    'pay_directly' => 'false',
    // Decimos que envie los correos.
    'send_emails' => 'true',
    // Si contamos con algun sitio, podemos redireccionar al usuario
    // a esta URL una vez que pague.
    'return_url' => '',
    // Le damos un tiempo de expiración.
    'expires_date' => time() + 30 * 10,
    // Opcionalmente podemos asignar una URL de una imagen.
    'picture_url' => '',
  );
  // Recorremos los datos y se lo asignamos al servicio
  foreach($data as $name => $value) {
    $khipu_service->setParameter($name, $value);
  }
  /**
   * En reemplazo de setParameter se podría usar
   * $this->setParameters($data);
   */
  // Agregamos un destinatario con un monto
  $khipu_service->addRecipient('Cliente', 'cliente@gmai.com', 25000);

  // Lo enviamos
  $json = $khipu_service->send();

```

### 2) Crear Página de Pago

Crear una página de pago también se requiere identificarse, a continuación un
ejemplo:

```php
<?php
  // ...
  require_once "Khipu.php";

  $Khipu = new Khipu();
  $Khipu->authenticate($receiver_id, $llave);
  $khipu_service = $Khipu->loadService('CreatePaymentPage');

  $data = array(
    'subject' => 'Título del pago',
    'body' => 'Descripción del producto',
    'amount' => 10000,
    // Página de exito
    'return_url' => $return_url,
    // Página de fracaso
    'cancel_url' => $cancel_url,
    'transaction_id' => 1,
    // Dejar por defecto un correo para recibir el comprobante
    'payer_email' => 'cliente@gmail.com',
    // url de la imagen del producto o servicio
    'picture_url' => $picture_url,
    // Opcional
    'custom' => 'Custom Variable',
    // definimos una url en donde se notificará del pago
    'notify_url' => $notify_url,
  );
  // Recorremos los datos y se lo asignamos al servicio.
  foreach ($data as $name => $value) {
    $khipu_service->setParameter($name, $value);
  }
  // Luego imprimimos el formulario html
  echo $khipu_service->renderForm();

```

### 3) Recibiendo y validando la notificación de un pago

Este servicio debe ser utilizado en la página que recibirá el POST desde
Khipu y no require identificar al cobrador.
A continuación un ejemplo:

```php
<?php
  // ...
  require_once "Khipu.php";

  $Khipu = new Khipu();
  // No necesitamos identificar al cobrador para usar este servicio.

  $api_version = $_POST['api_version'];

  if($api_version == '1.2') {
	$khipu_service = $Khipu->loadService('VerifyPaymentNotification');
  	$khipu_service->setDataFromPost();
	$response = $khipu_service->verify();
	if($response['response'] == 'VERIFIED'){
		//el pago esta verificado, hacemos algo al respecto
	}

  } else if ($api_version == '1.3') {
	$Khipu->authenticate($receiver_id, $llave);
	$khipu_service = $Khipu->loadService('GetPaymentNotification');
  	$khipu_service->setDataFromPost();
	$response = json_decode($khipu_service->consult());
	
	// validamos que la notificacion de pago corresponda con una solicitud de pago esperada
	if($response->transaction_id == $mi_transaction_id &&  $response->receiver_id == $receiver_id, $response->amount == $mi_amount) {
		// la notificacion esta ok, la estaba esperando y debo procesar el pedido
	} else {
		// la notificacion no es para mi o no la estaba esperando, la ignoro
	}
	

  } else {
	// api de notificacion no soportada
  }


```

### 4) Verificar Estado de una cuenta Khipu

Este servicio permite consultar el estado de una cuenta khipu, la cual retorna
un json mencionando el ambiente en que se encuentra y si puede recibir pagos.
A continuación un ejemplo:

```php
<?php
  // ...
  require_once "Khipu.php";
  $Khipu = new Khipu();
  $Khipu->authenticate($receiver_id, $llave);
  $khipu_service = $Khipu->loadService('ReceiverStatus');

  // Aquí se hace la consulta a khipu sobre la cuenta.
  $json = $khipu_service->consult();

```

En la carpeta examples existen otros ejemplos.

## Extra

La clase Khipu cuenta con dos funciones estáticas, las cuales son:

### getUrlService()
```php
<?php
  // ...
  require_once "Khipu.php";

  // Imprime https://khipu.com/api/1.3/verifyPaymentNotification
  echo Khipu::getUrlService('VerifyPaymentNotification');
?>
```
Esta función recibe el nombre del servicio y retorna la URL de Khipu que
corresponde.

### getButtonsKhipu()
```php
<?php
  // ...
  require_once "Khipu.php";

  $buttons = Khipu::getButtonsKhipu();
?>
```
Esta función retorna la lista de links de los botones de Khipu.com, la pueden ver
aquí: https://khipu.com/page/botones-de-pago
