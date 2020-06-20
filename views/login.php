<?php
include("header.php");

if(isset($_GET['cad'])) {
    $cad = $_GET['cad'];
    if ($cad == "1") {
        if ($_POST['email'] <> '') {
            $_where['email'] = $_POST['email'];
        }
        if ($_POST['password'] <> '') {
            $_where['password'] = md5($_POST['password']);
        }
        $_cons = $__query->QueryPDO('tab_user', $_where);

        if($_cons['contador'] <> '0') {
            $_where['id_user'] = $_cons[0]['id'];
            $_login = $__session->Logausuario($_where);
            header("Location: ../index.php");
        }
    }
}
?>
    <form action="login.php?cad=1" method="POST">
        <section class="container">
            <div class="content bg-light">
                <h1 class="text-center">ENTRAR</h1><br>
                    <label for="email">Digite seu e-mail:</label><br>
                    <input type="email" name="email" placeholder=" ✉ Insira seu e-mail"><br>

                    <label for="email">Digite sua senha:</label><br>
                    <input type="password" name="password" placeholder=" 🗝 Insira sua senha"><br>

                    <center><button class="btn-submit text-center">LOGIN</button></center><br>
                    <p class="text-center">Não possui cadastro? <a href="register.php">Crie uma conta</a>.</p>
            </div>
        </section>
    </form>

<?php include("footer.php"); ?>