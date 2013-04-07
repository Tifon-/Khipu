<?php 
  require_once "setting_example.php";
  require_once "../src/Khipu.php";
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Example Khipu Library</title>
  <meta name="description" content="Example Khipu Library">
</head>
<body style="margin:0; background: #f5f5f5;">
  <div style="padding:10px;">
    <h1 style="color: #2F2001;">Example Khipu Library</h1>
    <div>Esto es un ejemplo usando la <a href="https://github.com/mnico/Khipu" target="_blank">librería Khipu</a> para crear un boton de pago</div>
    <div>
      <form method="POST">
        <div>
          <label>ID Cobrador</label>
          <input required type="number" name="receiver_id" value="<?php print isset($_POST['receiver_id']) ? $_POST['receiver_id'] : '';?>"/> 
        </div>
        <div>
          <label>Llave</label>
          <input required size="43" type="textfield" name="secret" value="<?php print isset($_POST['secret']) ? $_POST['secret'] : '';?>"/> 
        </div>
        <div>
          <label>Monto</label>
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
          $khipu_service = $Khipu->loadService('CreatePaymentPage');
          
          if ($_POST['amount'] > 0) {
            $data['amount'] = $_POST['amount'];
          }
        ?>
        <div>
          <p>Los datos a enviar son:</p>
          <ul>
            <?php foreach ($data as $name => $value):?>
              <li><strong><?php print $name;?></strong> = <?php print $value;?></li>
              <?php 
                // Le asignamos los valores
                $khipu_service->setParameter($name, $value);?>
            <?php endforeach;?>
          </ul>
          
          <div>
            <?php 
              // Generamos el formulario.
              print $khipu_service->renderForm();?>
          </div>
        </div>
      <?php else: ?>
        <div>Aquí se generará el boton de pago.</div>
      <?php endif;?>
    </div>
   
  </div>
</body>
</html>