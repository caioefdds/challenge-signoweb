<?php
    include("header.php");
    error_reporting(0);

if(isset($_GET['cad'])) {
        $cad = $_GET['cad'];
        if ($cad == "1") {
            if ($_POST['email'] <> '') {
                $_dados['email'] = $_POST['email'];
            }
            if ($_POST['password'] <> '') {
                $_dados['password'] = md5($_POST['password']);
            }

            $_where['email'] = $_dados['email'];
            $_verify = $__query->QueryPDO('tab_user', $_where);
            if($_verify['contador'] <> '0') {
                echo "<script>alert(`Esse e-mail já está em uso`);</script>";
            } else {
                $_register = $__query->RegisterUser($_dados);

                if(is_nan($_register)) {
                    echo "<script>alert(`Falha ao criar a conta`);</script>";
                } else {
                    echo "<script>alert(`Conta criada com sucesso`);</script>";
                    header("Location: ../index.php");
                }
            }
        }
    }
?>
    <form action="register.php?cad=1" method="POST">
            <section class="container">
                <div class="content bg-light">
                    <h1 class="text-center">REGISTRAR</h1><br>
                    <label for="email">Digite seu e-mail:</label><br>
                    <input type="email" name="email" placeholder=" ✉ Insira seu e-mail"><br>

                    <label for="email">Digite sua senha:</label><br>
                    <input type="password" name="password" placeholder=" 🗝 Insira sua senha"><br>

                    <center><button class="btn-submit text-center">REGISTER</button></center><br>
                    <p class="text-center">Já possui cadastro? <a href="login.php">Faça login agora</a>.</p>
                </div>
            </section>
    </form>

<?php include("footer.php"); ?>
