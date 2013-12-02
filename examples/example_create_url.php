<?php
  /**
   * En este ejemplo crearemos un botón de pago.
   *
   * Para probarlo debes contar con una cuenta de cobrador que lo puedes
   * conseguir gratuitamente ingresando a Khipu.com
   */
  require_once "../src/Khipu.php";
  $amount = 10000;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Example Khipu Library | Crear URL para un pago</title>
  <meta name="description" content="Example Khipu Library">
  <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
  <div class="wrapper">
    <header>
      <h1 class="title">Crear URL para un pago</h1>
    </header>
    <div class="breadcrumb"><a href="index.php">Inicio</a></div>
    <div class="content">
      <div>Esto es un ejemplo usando la <a href="https://github.com/mnico/Khipu" target="_blank">Biblioteca Khipu</a> para crear URL para un pago</div>
      <div>
        <form method="POST">
          <div class="field">
            <label class="label-example">ID Cobrador</label>
            <input required type="number" name="receiver_id" value="<?php print isset($_POST['receiver_id']) ? $_POST['receiver_id'] : '';?>"/>
          </div>
          <div class="field">
            <label class="label-example">Llave</label>
            <input required size="43" type="textfield" name="secret" value="<?php print isset($_POST['secret']) ? $_POST['secret'] : '';?>"/>
          </div>
          <div class="field">
            <label class="label-example">Monto</label>
            <input required min="1" type="number" name="amount" value="<?php print isset($_POST['amount']) ? $_POST['amount'] : $amount;?>"/>
          </div>
          <input type="submit" value="Generar Botón"/>
        </form>
        <?php if (isset($_POST['receiver_id']) && isset($_POST['secret'])) :?>
          <?php
            $Khipu = new Khipu();
            // Nos identificamos
            $Khipu->authenticate($_POST['receiver_id'], $_POST['secret']);
            // Cargamos el servicio para crear el boton
            $khipu_service = $Khipu->loadService('CreatePaymentURL');
            $payer_email = 'cliente@gmail.com';

            $picture_url = 'https://s3.amazonaws.com/static.khipu.com/buttons/100x50.png';

            $data = array(
              'subject' => 'Pago de Ejemplo',
              'body' => 'Estamos usando la librería de Khipu, creamos una URL',
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
            if ($_POST['amount'] > 0) {
              $data['amount'] = $_POST['amount'];
            }
          ?>
          <div>
            <?php
              $khipu_service->setParameters($data);
              print $khipu_service->createUrl();
            ?>
          </div>

        <?php else: ?>
          <div>Aquí se generará la url.</div>
        <?php endif;?>
      </div>
    </div>

  </div>
</body>
</html>