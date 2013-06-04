<?php
  require_once "../src/Khipu.php";
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Example Khipu Library | Ver estado de la cuenta Khipu</title>
  <meta name="description" content="Example Khipu Library">
  <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
  <div class="wrapper">
    <header>
      <h1 class="title">Ver estado de la cuenta Khipu</h1>
    </header>
    <div class="breadcrumb"><a href="index.php">Inicio</a></div>
    <div class="content">
      <div>Esto es un ejemplo usando la <a href="https://github.com/mnico/Khipu" target="_blank">Biblioteca Khipu</a> para actualizar versión de la notificación</div>
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
            <label class="label-example">Versión</label>
            <input required size="3" type="textfield" name="api_version" value="<?php print isset($_POST['api_version']) ? $_POST['api_version'] : '1.2';?>"/>
          </div>
          <div class="field">
            <label class="label-example">Url (Opcional)</label>
            <input size="43" type="textfield" name="url" value="<?php print isset($_POST['url']) ? $_POST['url'] : '';?>"/>
          </div>
          <input type="submit" value="Actualizar"/>
        </form>
        <?php if (isset($_POST['receiver_id']) && isset($_POST['secret'])) :?>
          <div>Respuesta:
            <?php
              $Khipu = new Khipu();
              // Nos identificamos
              $Khipu->authenticate($_POST['receiver_id'], $_POST['secret']);
              $data = array(
                'api_version' => $_POST['api_version'],
                'url'         => $_POST['url'],
              );
              $service = $Khipu->loadService('UpdatePaymentNotificationUrl');
              $service->setParameters($data);
              print $service->update();
              print $service->getMessage();
            ?>
          </div>
        <?php endif;?>
      </div>
    </div>
  </div>
</body>
</html>
