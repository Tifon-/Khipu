<?php
  require_once "../src/Khipu.php";

  $parameters = array(
    'secret',
    'payment_id',
    'receiver_id',
  );

  if (!empty($_GET)) {
    foreach ($_GET as $key => $value) {
      if (!isset($_POST[$key]) && in_array($key, $parameters)) {
        $_POST[$key] = $value;
      }
    }
  }

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Example Khipu Library | Marcar un pago como rechazado</title>
  <meta name="description" content="Example Khipu Library">
  <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
  <div class="wrapper">
    <header>
      <h1 class="title">Marcar un pago como rechazado</h1>
    </header>
    <div class="breadcrumb"><a href="index.php">Inicio</a></div>
    <div class="content">
      <div>Esto es un ejemplo usando la <a href="https://github.com/mnico/Khipu" target="_blank">Biblioteca Khipu</a> para marcar un pago como rechazado</div>
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
            <label class="label-example">Payment ID</label>
            <input required type="textfield" name="payment_id" value="<?php print isset($_POST['payment_id']) ? $_POST['payment_id'] : '';?>"/>
          </div>
          <input type="submit" value="Marcar un pago como rechazado"/>
        </form>
        <?php if (isset($_POST['receiver_id']) && isset($_POST['secret']) && isset($_POST['payment_id'])) :?>
          <div>Respuesta:
            <?php
              $Khipu = new Khipu();
              // Nos identificamos
              $Khipu->authenticate($_POST['receiver_id'], $_POST['secret']);
              $data = array(
                'payment_id' => $_POST['payment_id'],
                'text' => 'Se rechaza por el example',
              );
              $service = $Khipu->loadService('SetRejectedByPayer');
              $service->setParameters($data);
              if ($service->set()) {
                print '<div class="message success">Se marco como rechazado el pago ' . $_POST['payment_id'];
                print '</div>';
              }
              else {
                print '<div class="message error">No se pudo marca como rechazado el pago ' . $_POST['payment_id'];
                print '<p>El mensaje de Khipu es: <em>' . $service->getMessage() . '</em></p>';
                print '</div>';
              }
            ?>
          </div>
        <?php endif;?>
      </div>
    </div>
  </div>
</body>
</html>
