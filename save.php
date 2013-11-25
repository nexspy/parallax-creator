<?php
if(file_put_contents('data/new.json',json_encode($_POST))){
  print "SAVED";
}
