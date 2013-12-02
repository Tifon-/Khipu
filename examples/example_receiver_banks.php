<?php
  require_once "../src/Khipu.php";
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Example Khipu Library | Obtener listado de bancos</title>
  <meta name="description" content="Example Khipu Library">
  <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
  <div class="wrapper">
    <header>
      <h1 class="title">Obtener listado de bancos</h1>
    </header>
    <div class="breadcrumb"><a href="index.php">Inicio</a></div>
    <div class="content">
      <div>Esto es un ejemplo usando la <a href="https://github.com/mnico/Khipu" target="_blank">Biblioteca Khipu</a> para obtener listado de bancos.</div>
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
          <input type="submit" value="Consultar Bancos"/>
        </form>
        <?php if (isset($_POST['receiver_id']) && isset($_POST['secret'])) :?>
          <div>Respuesta:
            <?php
              $Khipu = new Khipu();
              // Nos identificamos
              $Khipu->authenticate($_POST['receiver_id'], $_POST['secret']);
              $service = $Khipu->loadService('ReceiverBanks');
              print $service->consult();
            ?>
          </div>
        <?php endif;?>
      </div>
    </div>
  </div>
</body>
</html>
