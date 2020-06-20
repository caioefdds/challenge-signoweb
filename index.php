<?php error_reporting(0); ?>

        <?php 
            include("views/header_index.php");
            if(!isset($_SESSION['email'])) {
                header("Location: views/login.php");
                die;
            }

            $cons = $__query->QueryPDO('tab_enquete','','ORDER BY date_end desc');
        ?>

            <div class="container">
                <a href="views/new_poll.php" class="btn-submit">NOVA ENQUETE</a>
                <a href="views/my_poll.php" class="btn-submit">MINHAS ENQUETES</a>
                <a href="views/logout.php" class="btn-submit color-danger">SAIR</a>
            </div>

            <div class="container-lg column">
                <?php for($a=0; $a<$cons['contador']; $a++) {
                    $status = $__query->DateTest($cons[$a]['date_start'], $cons[$a]['date_end'])?>
                <form action="views/show_poll.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $cons[$a]['id'] ?>">
                    <div class="poll row">
                        <div class="item column">
                            <p><?php echo $cons[$a]['title']; ?></p>
                        </div>
                        <div class="item column">
                            <p class="data"><?php echo $cons[$a]['date_start']; ?></p>
                            <p class="data"><?php echo $cons[$a]['date_end']; ?></p>
                        </div>
                        <div class="item column">
                            <button class="btn-enquete <?php echo $status['color']?>"> <?php echo $status['msg']?></button>
                        </div>
                    </div>
                </form>
                <?php } ?>
            </div>
