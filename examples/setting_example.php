<?php



$payer_email = 'cliente@gmail.com';

$amount = 10000;

$picture_url = 'https://s3.amazonaws.com/static.khipu.com/buttons/100x50.png';

$data = array(
  'subject' => 'Pago de Ejemplo',
  'body' => 'Estamos usando la librería de Khipu.',
  'amount' => $amount,
  'transaction_id' => 1,
  // Dejar por defecto un correo para recibir el comprobante
  'payer_email' => $payer_email,
  // url de la imagen del producto o servicio
  'picture_url' => $picture_url,
  // Opcional
  'custom' => 'Custom Variable',
  // definimos una url en donde se notificará del pago
  'notify_url' => '', 
);
