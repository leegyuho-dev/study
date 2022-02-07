<?php
require_once 'includes/init.php';

$page = 1;
$serialno = 11;
$year = 'all';
$month = 'all';

if (isset($_REQUEST['page'])) {
  $page = $_REQUEST['page'];
}
if (isset($_REQUEST['year'])) {
  $year = $_REQUEST['year'];
}
if (isset($_REQUEST['month'])) {
  $month = $_REQUEST['month'];
}
if (isset($_REQUEST['serialno'])) {
  $serialno = $_REQUEST['serialno'];
}

if (isset($_POST['delete'])) {
  $serialno = $_POST['serialno'];
  $tranqnty = $_POST['tranqnty'];
  $trancode = $_POST['trancode'];

  $sql = "SELECT * FROM itemmast 
          WHERE itemcode = '$trancode'";
  $res = mysqli_query($db, $sql);
  $a = mysqli_fetch_assoc($res);
  $inventry = $a['inventry'] - $tranqnty;

  $sql = "DELETE FROM inntran
          WHERE serialno = '$serialno'";
  // echo $sql.'<br>';
  mysqli_query($db, $sql);

  $sql = "UPDATE itemmast
          SET inventry = '$inventry'
          WHERE itemcode = '$trancode'";
  // echo $sql.'<br>';
  mysqli_query($db, $sql);

  mysqli_query($db, $sql);
  $msg = '레코드 삭제 완료';
  $url = 'inntran_edit.php?page='.$page.'&year='.$year.'&month='.$month;
  sendMsg($msg, $url);
}

$sql = "SELECT inntran.*, 
        itemmast.descript AS item_name,
        itemmast.itemspec AS item_spec,
        itemmast.inventry AS item_invn
        FROM inntran 
        JOIN itemmast ON inntran.trancode = itemmast.itemcode
        WHERE serialno = '$serialno'
        ";
$res = mysqli_query($db, $sql);

?>
<!-- html -->
<?php
  include 'includes/_header.php';
?>
<h3>입고 삭제</h3>
<hr>
<!-- contents -->
<div class="tbContents">

  <table cellpadding="3" cellspacing="0">
    <tr>
      <th>입고일자</th>
      <th>입고제품</th>
      <th>입고</th>
      <th>재고</th>
      <th>입고단가</th>
      <!-- <th>입출구분</th> -->
    </tr>
    <?php
      while ($a = mysqli_fetch_assoc($res)) {
        $tranname = $a['item_name'].' ('.$a['item_spec'].')';
        $tranprce = number_format($a['tranprce']).'원';
        $tranqnty = $a['tranqnty'];
        $trancode = $a['trancode'];
        echo '<tr>';
        echo '<td>'.$a['trandate'].'</td>';
        echo '<td class="left">'.$tranname.'</td>';
        echo '<td class="right">'.$tranqnty.'</td>';
        echo '<td class="right">'.$a['item_invn'].'</td>';
        echo '<td class="right">'.$tranprce.'</td>';
        // echo '<td>'.$a['trankind'].'</td>';
        echo '</tr>';
      }
    ?>
  </table>

</div>

<br>
<span class="red">
<b>레코드를 삭제하시겠습니까?</b>
</span>
<br><br>

<form method="post" action="">
  <input type="hidden" name="page" value="<?=$page?>">
  <input type="hidden" name="year" value="<?=$year?>">
  <input type="hidden" name="month" value="<?=$month?>">
  <input type="hidden" name="serialno" value="<?=$serialno?>">
  <input type="hidden" name="tranqnty" value="<?=$tranqnty?>">
  <input type="hidden" name="trancode" value="<?=$trancode?>">
  <input type="submit" name="delete" value="Yes">
  <input type="button" value="No"
    onclick="location.href='<?
      echo 'inntran_edit.php?page='.$page.'&year='.$year.'&month='.$month;
    ?>'">
</form>

<!-- contents -->
<?php
  include 'includes/_footer.php';
?>