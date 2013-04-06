# Khipu

Versión Librería: 1.0

Versión API Khipu: 1.1

Librería PHP para utilizar los servicios de Khipu.com

La documentación de Khipu se puede ver desde aquí: https://khipu.com/page/api

## Introducción

Khipu cuenta con tres servicios, los cuales son:

1) Crear Cobros y enviarlos por Mail.

2) Crear Página de Pago.

3) Recibiendo y validando la notificación de un pago.

Para utilizar estos servicios se debe cargar el archivo Khipu.php

## 1) Crear Cobros y enviarlos por Mail

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
    'send_emails' => 'true', // Decimos que envie los correos
    'return_url' => '', // Si contamos con algun sitio, podemos redireccionarlo
    'expires_date' => time() + 30 * 10, // Le damos un tiempo de expiración
    'picture_url' => '', // Opcionalmente podemos asignar una URL de una imagen
  );
  // Recorremos los datos y se lo asignamos al servicio
  foreach($data as $name => $value) {
    $khipu_service->setParameter($name, $value);
  }
  // Agregamos un destinatario con un monto
  $khipu_service->addRecipient('Cliente', 'cliente@gmai.com', 25000);
  
  // Lo enviamos
  $json = $khipu_service->send();
  
```

## 2) Crear Página de Pago

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

## 3) Recibiendo y validando la notificación de un pago

Este servicio debe ser utilizado en la página que recibirá el POST desde
Khipu y no require identificar al cobrador.
A continuación un ejemplo:

```php
<?php
  // ...
  require_once "Khipu.php";
  
  $Khipu = new Khipu();
  // No necesitamos identificar al cobrador para usar este servicio.
  $khipu_service = $Khipu->loadService('VerifyPaymentNotification');
  // Adjuntamos los valores del $_POST en el servicio.
  $khipu_service->setDataFromPost();
  // Hacemos una solicitud a Khipu para verificar.
  $response = $khipu_service->verify();
  if ($response['response'] == 'VERIFIED') {
    // Hacemos algo al respecto...
  }
  
```

## Extra

La clase Khipu cuenta con dos funciones estáticas, las cuales son:

1) Khipu::getUrlService($service);

Esta función recibe el nombre del servicio y retorna la URL de Khipu

2) Khipu::getButtonsKhipu();

Esta función retorna la lista de botones que nos da a dispoción Khipu.com,
la lista la pueden ver aquí: https://khipu.com/page/botones-de-pago
