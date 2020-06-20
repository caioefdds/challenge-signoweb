<?php
include("header.php");

    if(!isset($_SESSION['email'])) {
        header("Location: login.php");
        die;
    }

    if(isset($_GET['cad'])) {
        $cad = $_GET['cad'];
        if ($cad == "1") {

            /* Verificar se o título está setado */
            if($_POST['title'] <> '') {
                $_dados['title'] = $_POST['title'];
                $_dados['date_start'] = $_POST['date_start'];
                $_dados['date_end'] = $_POST['date_end'];

                /* Verificar se a data de início é maior que a de fim */
                if($_dados['date_start'] > $_dados['date_end']) {
                    echo "Insira corretamente as datas!";
                } else {
                    $where['id'] = $_POST['id_enquete'];
                    $enquete = $__query->UpdatePDO($_dados, 'tab_enquete', $where);

                    if($enquete == '1' || $enquete == '0') {
                        $_where['id_enquete'] = $_POST['id_enquete'];
                        $delete_option = $__query->DeleteOptions($_where);

                        if($delete_option >= '0') {
                            $count = $_POST['num_opcoes'];
                            $a = 1;
                            while($a <= $count) {
                                if($_POST["opcao_".$a] <> '') {
                                    $opcao['descricao'] = $_POST["opcao_".$a];
                                    $opcao['id_enquete'] = $_POST['id_enquete'];
                                    $opcao['votes'] = 0;

                                    $insert_opcao = $__query->InsertPDO($opcao, 'tab_enquete_opcao');
                                }
                                $a++;
                            }
                        }
                    }
                }
            }
        }
    }

    if(isset($_POST['id_poll']) && $_POST['id_poll'] <> "") {
        $cons = $__query->ShowPoll($_POST['id_poll']);
    } else {
        header("Location: my_poll.php");
        die;
    }
?>
    <div class="container">
        <a href="../index.php" class="btn-submit">Voltar</a>
    </div>
    <form action="edit_poll.php?cad=1" method="POST">
        <section class="container">
            <div class="form column bg-light">
                <div>
                    <h1 class="text-center">EDITAR ENQUETE</h1><br>
                    <label for="title">Insira o título da enquete:</label><br>
                    <input type="text" name="title" value="<?php echo $cons[0]['title'];?>" placeholder="Digite o título da enquete"><br>
                    <div class="row flex space-between">
                        <div>
                            <label for="date_start">Data de início:</label>
                            <input type="date" name="date_start" value="<?php echo $cons[0]['date_start'];?>"><br>
                        </div>
                        <div>
                            <label for="date_end">Data de fim:</label>
                            <input type="date" name="date_end" value="<?php echo $cons[0]['date_end'];?>"><br>
                        </div>
                    </div>
                </div>
                <div class="item-form column">

                    <?php for($a=0; $a < $cons['options']['contador']; $a++) { ?>
                    <b>OPÇÃO <?php echo $a+1; ?>:</b><br>
                    <input type="text" name="opcao_<?php echo $a+1; ?>" value="<?php echo $cons['options'][$a]['descricao']; ?>" placeholder="Digite a opção"><br>
                    <?php } ?>

                    <button id="botao_plus" class="btn-submit" data-num="<?php echo $cons['options']['contador']; ?>" onclick="AdicionarCampo(this)">+</button><br>
                    <button type="submit" class="btn-submit">EDITAR</button>
                </div>
                <input type="hidden" id="num_opcoes" value="<?php echo $cons['options']['contador']; ?>" name="num_opcoes">
                <input type="hidden" value="<?php echo $cons[0]['id'];?>" name="id_enquete">
            </div>
        </section>
    </form>

<?php include("footer.php"); ?>