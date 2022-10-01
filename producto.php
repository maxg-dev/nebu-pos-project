<?php
require_once "config.php";
// Define variables and initialize with empty values
$nombre = $descripcion = $precio = "";
$nombre_err = $descripcion_err = $precio_err = "";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

  // Validate name
  $input_nombre = trim($_POST["nombre"]);
    if(empty($input_nombre)){
      $nombre_err = "Porfavor ingresar nombre del producto.";
    } elseif(!filter_var($input_nombre, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
      $nombre_err = "Porfavor ingresar un nombre válido.";
    } else{
      $nombre = $input_nombre;
    }

    // Validate descripcion
    $input_descripcion = trim($_POST["descripcion"]);
    if(empty($input_descripcion)){
      $descripcion_err = "Porfavor ingresar descripcion.";
    } else{
      $descripcion = $input_descripcion;
    }

    // Validar precio
    $input_precio = trim($_POST["precio"]);
    if(empty($input_precio)){
      $precio_err = "Porfavor ingresar el precio.";
    } elseif(!ctype_digit($input_precio)){
      $precio_err = "Porfavor ingresar valor positivo.";
    } else{
        $precio = $input_precio;

      }
      // Check input errors before inserting in database
      if(empty($nombre_err) && empty($descripcion_err) && empty($precio_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO producto (nombre, descripcion, precio) VALUES (?, ?, ?)";
        if($stmt = mysqli_prepare($link, $sql)){
          // Bind variables to the prepared statement as parameters
          mysqli_stmt_bind_param($stmt, "sss", $param_nombre, $param_descripcion, $param_precio);
          // Set parameters
          $param_nombre = $nombre;
          $param_descripcion = $descripcion;
          $param_precio = $precio;
          // Attempt to execute the prepared statement
          if(mysqli_stmt_execute($stmt)){
            // Records created successfully. Redirect to landing page
            header("location: index.php");
            exit();
          } else{
            echo "Oops! Something went wrong. Please try again later.";
          }
        }
        // Close statement
        mysqli_stmt_close($stmt);
      }
      // Close connection
      mysqli_close($link);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Nuevo producto</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<style>
  .wrapper{
  width: 600px;
  margin: 0 auto;
  }
</style>

</head>
<body>

<?php require 'header.php'; ?>

<div class="wrapper">
<div class="container-fluid">
<div class="row">
<div class="col-md-12">
<h2 class="mt-5">Añadir nuevo producto</h2>
<p>Porfavor llenar formulario para ingresarlo a la base de datos.</p>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

<div class="form-group">
  <label>Nombre</label>
  <input type="text" name="nombre" class="form-control <?php echo (!empty($nombre_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nombre; ?>"required maxlength="50">
  <span class="invalid-feedback"><?php echo $nombre_err;?></span>
</div>

<div class="form-group">
<label>Descripcion</label>
<textarea name="descripcion" class="form-control <?php echo (!empty($descripcion_err)) ? 'is-invalid' : ''; ?>" required maxlength="150"><?php echo $descripcion; ?></textarea>
<span class="invalid-feedback"><?php echo $descripcion_err;?></span>
</div>

<div class="form-group">
<label>Precio</label>
<input type = "number" name="precio" class="form-control <?php echo (!empty($precio_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $precio; ?>" required maxlength="6">
<span class="invalid-feedback"><?php echo $precio_err;?></span>
</div>

<input type="submit" class="btn btn-success" value="Enviar">
<a href="index.php" class="btn btn-secondary ml-2">Cancelar</a>
</form>
</div>
</div>
</div>
</div>
<?php require 'footer.php'; ?>
</body>
</html>
