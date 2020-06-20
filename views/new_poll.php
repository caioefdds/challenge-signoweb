<?php
include("header.php");

if(!isset($_SESSION['email'])) {
    header("Location: login.php");
    die;
}

if(isset($_GET['cad'])) {
    $cad = $_GET['cad'];
    if ($cad == "1") {
        if($_POST['title'] <> '') {
            $_dados['title'] = $_POST['title'];
            $_dados['date_start'] = $_POST['date_start'];
            $_dados['date_end'] = $_POST['date_end'];
            $_dados['id_user'] = $_SESSION['id_user'];

            if($_dados['date_start'] > $_dados['date_end']) {
                echo "Insira corretamente as datas!";
            } else {
                $enquete = $__query->InsertPDO($_dados, 'tab_enquete');

                if(!is_nan($enquete)) {
                    $count = $_POST['num_opcoes'];
                    $a = 1;
                    while($a <= $count) {

                        if($_POST["opcao_".$a] <> '') {
                            $opcao['descricao'] = $_POST["opcao_".$a];
                            $opcao['id_enquete'] = $enquete;
                            $opcao['votes'] = 0;

                            $insert_opcao = $__query->InsertPDO($opcao, 'tab_enquete_opcao');
                        }
                        $a++;
                    }
                    header("Location: ../index.php");
                }
            }
        }
    }
}
?>
    <div class="container">
        <a href="../index.php" class="btn-submit">Voltar</a>
    </div>
    <form action="new_poll.php?cad=1" method="POST">
        <section class="container">
            <div class="form column bg-light">
                <div>
                    <h1 class="text-center">CRIAR NOVA ENQUETE</h1><br>
                    <label for="title">Insira o título da enquete:</label><br>
                    <input type="text" name="title" placeholder="Digite o título da enquete"><br>
                    <div class="row flex space-between">
                        <div>
                            <label for="date_start">Data de início:</label>
                            <input type="date" name="date_start"><br>
                        </div>
                        <div>
                            <label for="date_end">Data de fim:</label>
                            <input type="date" name="date_end"><br>
                        </div>
                    </div>
                </div>
                <div class="item-form column">
                    <b>OPÇÃO 1:</b><br>
                    <input type="text" name="opcao_1" placeholder="Digite a opção" required><br>
                    <b>OPÇÃO 2:</b><br>
                    <input type="text" name="opcao_2" placeholder="Digite a opção" required><br>
                    <b>OPÇÃO 3:</b><br>
                    <input type="text" name="opcao_3" placeholder="Digite a opção" required><br>
                    <button id="botao_plus" class="btn-submit" data-num="3" onclick="AdicionarCampo(this)">+</button><br>
                    <button type="submit" class="btn-submit">CRIAR</button>
                </div>
                <input type="hidden" id="num_opcoes" value="3" name="num_opcoes">
            </div>
        </section>
    </form>

<?php include("footer.php"); ?>