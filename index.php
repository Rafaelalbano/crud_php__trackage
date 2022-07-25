<?php
require_once 'motorista.php';
$conn = new Motorista("cadastro-motorista", "localhost", "root", "");
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Motoristas Trackage</title>
</head>

<body>
    <nav class="navbar navbar-light bg-light">
        <a class="navbar-brand" href="https://www.trackage.com.br"><img src="img/trackage2.png" alt="Trackage" class="logo"></a>
    </nav>
    <?php
    if (isset($_POST['nome'])) {
        //Quando clicou no botão cadastrar ou editar
        //Editar
        if (isset($_GET['update']) && !empty($_GET['update'])) {
            $upd = addslashes($_GET['update']);
            $nome = addslashes($_POST['nome']);
            $cpf = addslashes($_POST['cpf']);
            $cnh = addslashes($_POST['cnh']);
            if (!empty($nome) && !empty($cpf) && !empty($cnh)) {
                $conn->atualizarDados($upd, $nome, $cpf, $cnh);
                header("location: index.php");
            } else {
    ?>
                <div class="alert alert-danger">
                    <h4>Preencha todos os campos.</h4>
                </div>
                <?php
            }
        }

        //Cadastrar
        else {
            $nome = addslashes($_POST['nome']);
            $cpf = addslashes($_POST['cpf']);
            $cnh = addslashes($_POST['cnh']);
            if (!empty($nome) && !empty($cpf) && !empty($cnh)) {
                if (!$conn->cadastrarMotorista($nome, $cpf, $cnh)) {
                ?>
                    <div class="alert alert-danger">
                        <h4>CPF já cadastrado!</h4>
                    </div>
                <?php
                }
            } else {
                ?>
                <div class="alert alert-danger">
                    <h4>Preencha todos os campos.</h4>
                </div>
    <?php
            }
        }
    }
    ?>

    <?php
    if (isset($_GET['update'])) {
        $update = addslashes($_GET['update']);
        $result = $conn->buscarDadosMotorista($update);
    }

    ?>

    <section id="tela_cadastro">
        <form action="" method="POST">
            <div class="form-group">
                <h3>CADASTRAR MOTORISTA</h3>
                <hr>
                <label for="nome">Nome</label>
                <input type="text" name="nome" class="form-control" id="nome" value="<?php if (isset($result)) {
                                                                                            echo $result['nome'];
                                                                                        } ?>">
                <label for="cpf">CPF</label>
                <input type="text" name="cpf" class="form-control" id="cpf" value="<?php if (isset($result)) {
                                                                                        echo $result['cpf'];
                                                                                    } ?>">
                <label for="cnh">CNH</label>
                <input type="text" name="cnh" class="form-control" id="cnh" value="<?php if (isset($result)) {
                                                                                        echo $result['cnh'];
                                                                                    } ?>">
                <input type="submit" value="<?php if (isset($result)) {
                                                echo "Atualizar";
                                            } else {
                                                echo "Cadastrar";
                                            } ?>">
                <img src="img/trackage.png" alt="Trackage" class="logo">
            </div>
        </form>
    </section>

    <section id="tela_listagem">
        <table class="table table-hover">
            <thead class="thead-dark">
                <tr id="titulo">
                    <th scope="col">Nome</th>
                    <th scope="col">CPF</th>
                    <th scope="col" colspan="2">CNH</th>
                </tr>
            </thead>
            <?php
            $dados = $conn->buscarDados();
            if (count($dados) > 0) {
                for ($i = 0; $i < count($dados); $i++) {
                    echo "<tr>";
                    foreach ($dados[$i] as $key => $value) {
                        if ($key != "id") {
                            echo "<td>" . $value . "</td>";
                        }
                    }
            ?>
                    <td class="buttons">
                        <a class="btn btn-success" href="index.php?update=<?php echo $dados[$i]['id']; ?>">Editar</a>
                        <a class="btn btn-danger" href="index.php?id=<?php echo $dados[$i]['id']; ?>">Excluir</a>
                    </td>

                <?php
                    echo "</tr>";
                }
            } else {

                ?>
        </table>
        <div class="aviso">
            <h4>Nenhum motorista foi encontrado para ser exibido.</h4>
        </div>
    <?php
            }
    ?>

    </section>
    <script src="../js/jquery-3.6.0.min.js" type="text/javascript"></script>
    <script src="../js/jquery.mask.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#cpf').mask('000.000.000-00');
            $('#cnh').mask('00000000000');
        })

        function get_data(id, nome) {
            document.getElementById('nome_motorista').innerHTML = nome;
            document.getElementById('nome_motorista_1').value = nome;
            document.getElementById('cod_motorista').value = id;
        }
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>

</html>

<?php
if (isset($_GET['id'])) {
    $id_motorista = addslashes($_GET['id']);
    $conn->excluirMotorista($id_motorista);
    header("location: index.php");
}

?>