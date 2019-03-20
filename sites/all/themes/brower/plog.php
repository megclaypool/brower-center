<?php

function debug_var($var)
{
  ob_start();
  var_dump($var);
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}

function plog($str)
{
  $log_file = fopen("/tmp/brower.log", 'a');
  fwrite($log_file, $str);
  fclose($log_file);
}
?>
