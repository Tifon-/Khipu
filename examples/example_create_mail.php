<?php
  /**
   * En este ejemplo crearemos un botón de pago.
   *
   * Para probarlo debes contar con una cuenta de cobrador que lo puedes
   * conseguir gratuitamente ingresando a Khipu.com
   */
  require_once "../src/Khipu.php";
  $amount = 25000;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Example Khipu Library | Crear un Cobro por Mail</title>
  <meta name="description" content="Example Khipu Library">
  <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
  <div class="wrapper">
    <header>
      <h1 class="title">Crear un Cobro por Mail</h1>
    </header>
    <div class="breadcrumb"><a href="index.php">Inicio</a></div>
    <div class="content">
      <div>Esto es un ejemplo usando la <a href="https://github.com/mnico/Khipu" target="_blank">Biblioteca Khipu</a> para crear un cobro</div>
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
          <div class="field">
            <label class="label-example">Correo</label>
            <input required type="email" name="email" value="<?php print isset($_POST['email']) ? $_POST['email'] : '';?>"/>
          </div>
          <input type="submit" value="Crear Cobro"/>
        </form>
        <?php if (isset($_POST['receiver_id']) && isset($_POST['secret']) && isset($_POST['email'])) :?>
          <div>Respuesta:
            <?php
              $Khipu = new Khipu();
              // Nos identificamos
              $Khipu->authenticate($_POST['receiver_id'], $_POST['secret']);
              // Cargamos el servicio para crear el boton
              // Luego cargamos el servicio.
              $khipu_service = $Khipu->loadService('CreateEmail');

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
                'expires_date' => time() + 30*24*60*60,
                // Opcionalmente podemos asignar una URL de una imagen.
                'picture_url' => '',
              );



              if ($_POST['amount'] > 0) {
               $amount = $_POST['amount'];
              }

              $khipu_service->setParameters($data);

              // Agregamos un destinatario con un monto
              $khipu_service->addRecipient('Example Name', $_POST['email'], $amount);

              // Lo enviamos
              $respuesta = $khipu_service->send();
            ?>
            <ul>
              <li>Bill ID: <?php print $respuesta['bill_id'];?></li>
              <li>Cobros:
                <ul>
                  <?php foreach($respuesta['list'] as $cobro) :?>
                    <li>
                      <ul>
                        <li>ID: <?php print $cobro['payment_id']?></li>
                        <li>Correo: <?php print $cobro['mail']?></li>
                        <li>Link: <a href="<?php print $cobro['link']?>" target="_blank"><?php print $cobro['link']?></a></li>
                        <?php
                          $query = http_build_query(array(
                            'payment_id' => $cobro['payment_id'],
                            'secret' => $_POST['secret'],
                            'receiver_id' => $_POST['receiver_id'],
                          ));
                        ?>
                        <li>Para consultar estado click <a target="_blank" href="example_payment_status.php?<?php print $query;?>">aquí</a></li>
                        <li>Para marcarlo como pagado hacer click <a target="_blank" href="example_payed_receiver.php?<?php print $query;?>">aquí</a></li>
                        <li>Para marcarlo como rechazado hacer click <a target="_blank" href="example_rejected_payer.php?<?php print $query;?>">aquí</a></li>
                      </ul>
                    </li>
                  <?php endforeach;?>
                </ul>
              </li>
            </ul>
          </div>
          <?php
            $query = http_build_query(array(
              'bill_id' => $respuesta['bill_id'],
              'secret' => $_POST['secret'],
              'receiver_id' => $_POST['receiver_id'],
            ));
          ?>
          <p>Para expirar este cobro, haga click <a target="_blank" href="example_bill_expired.php?<?php print $query;?>">aquí</a>.</p>
        <?php endif;?>
      </div>
    </div>

  </div>
</body>
</html>
