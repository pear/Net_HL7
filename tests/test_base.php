<?php

function ok($ok, $msg= "") {
  
  if ($ok) {
    echo "ok     - $msg\n";
  }
  else {
    echo "failed - $msg\n";
  }
}

?>