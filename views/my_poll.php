<?php
    include("header.php");
        if(!isset($_SESSION['email'])) {
            header("Location: views/login.php");
            die;
        }

        $where['id_user'] = $_SESSION['id_user'];
        $cons = $__query->QueryPDO('tab_enquete',$where,'ORDER BY date_end desc');
?>

        <div class="container">
            <a href="../index.php" class="btn-submit">Voltar</a>
        </div>
            <div class="container-lg column">
                <?php for($a=0; $a<$cons['contador']; $a++) {
                    $status = $__query->DateTest($cons[$a]['date_start'], $cons[$a]['date_end'])?>
                <form action="edit_poll.php" method="POST">
                    <input type="hidden" name="id_poll" value="<?php echo $cons[$a]['id'] ?>">
                    <div class="poll row">
                        <div class="item column">
                            <p><?php echo $cons[$a]['title']; ?></p>
                        </div>
                        <div class="item column">
                            <p class="data"><?php echo $cons[$a]['date_start']; ?></p>
                            <p class="data"><?php echo $cons[$a]['date_end']; ?></p>
                        </div>
                        <div class="item row">
                            <button class="btn-submit color-warning"> EDITAR </button>
                            <button class="btn-submit color-danger" onclick="DeletePoll('<?php echo $cons[$a]['id'] ?>')"> EXCLUIR </button>
                        </div>
                    </div>
                </form>
                <?php } ?>
            </div>

<?php include("footer.php"); ?>