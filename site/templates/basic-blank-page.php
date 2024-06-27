<?php

include ('./_head-blank.php'); 
echo $page->html ? $page->html : $page->body;
include ('./_foot-blank.php');
