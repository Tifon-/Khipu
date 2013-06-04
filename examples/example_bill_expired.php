<?php
  require_once "../src/Khipu.php";

  $parameters = array(
    'secret',
    'bill_id',
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
  <title>Example Khipu Library | Expirar un cobro</title>
  <meta name="description" content="Example Khipu Library">
  <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
  <div class="wrapper">
    <header>
      <h1 class="title">Expirar un cobro</h1>
    </header>
    <div class="breadcrumb"><a href="index.php">Inicio</a></div>
    <div class="content">
      <div>Esto es un ejemplo usando la <a href="https://github.com/mnico/Khipu" target="_blank">Biblioteca Khipu</a> para expirar un cobro</div>
      <div>
        <form method="POST" action="example_bill_expired.php">
          <div class="field">
            <label class="label-example">ID Cobrador</label>
            <input required type="number" name="receiver_id" value="<?php print isset($_POST['receiver_id']) ? $_POST['receiver_id'] : '';?>"/>
          </div>
          <div class="field">
            <label class="label-example">Llave</label>
            <input required size="43" type="textfield" name="secret" value="<?php print isset($_POST['secret']) ? $_POST['secret'] : '';?>"/>
          </div>
          <div class="field">
            <label class="label-example">Bill ID</label>
            <input required type="textfield" name="bill_id" value="<?php print isset($_POST['bill_id']) ? $_POST['bill_id'] : '';?>"/>
          </div>
          <input type="submit" value="Expirar Pago"/>
        </form>
        <?php if (isset($_POST['receiver_id']) && isset($_POST['secret']) && isset($_POST['bill_id'])) :?>
          <div>Respuesta:
            <?php
              $Khipu = new Khipu();
              // Nos identificamos
              $Khipu->authenticate($_POST['receiver_id'], $_POST['secret']);

              // Vamos a expirar el pago
              $data = array(
                'bill_id' => $_POST['bill_id'],
                'text' => 'Expirando pago de ejemplo',
              );
              $expired_service = $Khipu->loadService('SetBillExpired');
              $expired_service->setParameters($data);
              if ($expired_service->set()) {
                print '<div class="message success">Expiramos el cobro ' . $_POST['bill_id'] . '</div>';
              }
              else {
                print '<div class="message error">No se pudo expirar el cobro ' . $_POST['bill_id'];
                print '<p>El mensaje de Khipu es: <em>' . $expired_service->getMessage() . '</em></p>';
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
