<?php

class Controller_Home
{
	public function index()
	{
		echo "<img alt=\"1\" src=\"http://upload.wikimedia.org/wikipedia/commons/thumb/0/04/Jkr-ft1.png/30px-Jkr-ft1.png\" width=\"30\" height=\"23\" srcset=\"http://upload.wikimedia.org/wikipedia/commons/thumb/0/04/Jkr-ft1.png/45px-Jkr-ft1.png 1.5x, //upload.wikimedia.org/wikipedia/commons/thumb/0/04/Jkr-ft1.png/60px-Jkr-ft1.png 2x\" data-file-width=\"400\" data-file-height=\"300\" style=\"border-style: none; margin: 0px; color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\"><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\">";
		view::render("shared/home/index");
	}
}

?>