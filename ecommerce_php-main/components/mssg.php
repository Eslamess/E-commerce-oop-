<?php
  if(isset($_SESSION['mssg'])){
    echo "<div class = my-5 >".$_SESSION['mssg']."</div>";
    unset($_SESSION['mssg']);
  }
?>
