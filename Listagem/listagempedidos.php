<?php
$busca = filter_input(INPUT_GET, 'busca', FILTER_SANITIZE_STRING);
include '../lib/Conexao.php';

$pdo = Conexao::connect();
$sql = $pdo->query("SELECT * FROM pedido INNER JOIN clientes on pedido.idcliente = clientes.idcliente WHERE dataped LIKE '$busca%'");
$pedido = $sql->fetchAll(PDO::FETCH_OBJ);

if (isset($_GET["idpedido"])) {
  $codpedido = $_GET["idpedido"];
  $sql = $pdo->query("SELECT * FROM pedido_detalhe as ped INNER JOIN produtos as prod on prod.codigo = ped.codproduto WHERE idpedido = '$codpedido'");
  $pedido_detalhe = $sql->fetchAll(PDO::FETCH_OBJ);
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <title>Listagem Pedidos</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/listagemprod.css">

</head>

<body>

  <!-- NAVBAR -->
  <?php include "menu.php" ?>


  <section>
    <!-- MEIO -->
    <div class="table-responsive-lg col-lg-10" id="limite-tabela">
      <div class="p-5 text-white bg-dark rounded-3">

        <form method="get">
          <div class="row my-3">
            <div class="col-md-4">
              <input type="date" name="busca" id="busca" placeholder="Buscar Produto" class="form-control" value="<?= $busca ?>">
            </div>
            <div class="col d-flex align-items-end">
              <button type="submit" class="btn btn-outline-primary" style="margin-bottom: 50px !important; ">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                  <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                </svg></button>
              <button type="reset" id="limpar" class="btn btn-outline-primary" style="margin-bottom: 50px !important;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z" />
                  <path fill-rule="evenodd" d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z" />
                </svg></button>

            </div>
          </div>
        </form>
        <table class="table table-responsive table-dark  table-hover table-bordered text-center">


          <thead class="table-dark table-bordered">
            <thead>
              <tr>
                <td scope="col">Id Pedido</td>
                <td scope="col">Data</td>
                <td scope="col">Valor</td>
                <td scope="col">Cliente</td>
                <td scope="col">Excluir</td>
                <td scope="col">Cupom Fiscal</td>
                <!--Opções-->
              </tr>
            </thead>
          <tbody>
            <?php
           

              foreach ($pedido as $pedidos) {  ?>

                <tr>
                  <td><?php echo $pedidos->idpedido ?></td>
                  <td><?php echo $pedidos->dataped ?></td>
                  <td><?php echo $pedidos->valor ?></td>
                  <td><?php echo $pedidos->nome ?></td>
                  <td><a type="button" data-toggle="modal" data-target="#EpicModal<?php echo $pedidos->idpedido ?>" class="btn btn-outline-danger">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                        <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                      </svg>
                    </a></td>

                  <td><a href="../GerarPDF/index.php?idpedido=<?php echo $pedidos->idpedido;?>" class="btn btn-outline-primary" id="pdf">Informações</a></td>


                  <div class="modal fade" id="EpicModal<?php echo $pedidos->idpedido ?>" tabindex="-1" aria-labelledby="EpicModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title text-dark" id="EpicModalLabel">AVISO</h5>
                        </div>


                        <div class="modal-body text-dark">
                          Tem certeza que deseja excluir o Pedido?
                        </div>

                        <div class="modal-footer">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Não</button>

                          <a href="../Carrinho/delpedido.php?idpedido=<?php echo $pedidos->idpedido ?>" class="btn btn-success">Sim</a>
                        </div>
                      </div>
                    </div>
                  </div>

                </tr>
            <?php }

            ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <script src="../js/jquery-3.6.0.min.js"></script>
  <script src="../bootstrap/js/modal_bootstrap.min.js"></script>
  <script src="../bootstrap/js/bootstrap.js"></script>

</body>

</html>