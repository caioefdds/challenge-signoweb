<?php
include("header.php");

if(!isset($_SESSION['email'])) {
    header("Location: login.php");
    die;
}

if(isset($_POST['id']) && $_POST['id'] <> "") {
    $cons = $__query->ShowPoll($_POST['id']);

    $vote = $__query->DateTest($cons[0]['date_start'], $cons[0]['date_end']);

    if($vote['status'] == '0' || $vote['status'] == '2') {
        $status = 'disabled';
        $msg = " - " . $vote['msg'];
    } else {
        $status = '';
        $msg = '';
    }
} else {
    header("Location: ../index.php");
    die;
}
?>
    <div class="container">
        <a href="../index.php" class="btn-submit">Voltar</a>
    </div>
    <form action="show_poll.php?cad=1" method="POST">
        <section class="container-lg column bg-light">
            <h1 class="text-center <?php echo $vote['color'] ?>">ENQUETE <?php echo $msg ?></h1>
            <div class="poll-show column">
                <div class="item-form text-title-lg flex">
                    <br><?php echo $cons[0]['title']; ?>
                </div><br>
                <input type="hidden" id="id_enquete" value="<?php echo $cons[0]['id']; ?>">
                <input type="hidden" id="id_user" value="<?php echo $_SESSION['id_user']; ?>">
                <div class="column flex">

                <?php for($a=0; $a<$cons['options']['contador']; $a++) { ?>
                   <div class="item-vote space-arround">
                       <div class="vote-content flex-wrap text-center flex">
                            <p class="text-subtitle"><?php  echo $cons['options'][$a]['descricao'];?></p>
                       </div>
                       <div class="text-center  flex-wrap flex">
                           <button class="btn-enquete flex text-center color-success" onclick="processaVoto('<?php echo $cons['options'][$a]['id'] ?>','<?php echo $cons['options'][$a]['votes'] ?>')" <?php echo $status ?>>VOTAR</button>
                       </div>
                       <div class="text-center flex-wrap flex">
                           <span class="data flex text-center" id="votes_<?php echo $cons['options'][$a]['id'] ?>"> <?php  echo $cons['options'][$a]['votes'];?></span>
                       </div>
                   </div>
                <?php } ?>
                </div>
            </div>
        </section>
    </form>

<?php include("footer.php"); ?>