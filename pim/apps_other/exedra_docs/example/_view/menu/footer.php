This is some content inside <b>view:menu/footer</b>.<br>
Data extracted from this controller, through variable called $footer : <b><?php echo $footer;?></b><br>
And i wanna load another controller below
<?php controller::load("menu","sub_footer");?>
Because, when I echoed <b><?php echo $footer;?></b>, it still echo the same data.