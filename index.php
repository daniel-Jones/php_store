<!DOCTYPE html>
<?php
/*
 * this file is part of an ecommerce website developed as a TAFE project
 * Copyright Daniel Jones (daniel@danieljon.es) 2019
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
?>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Daisuki (≧◡≦)</title>
		<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
		<meta name="keywords" content="manga, yuri, doujin, doujinshi, lewd">
		<meta name="description" content="Buy some Manga, but not really..">
		<meta name="author" content="Daniel Jones">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Cache-control" content="public">
		<link rel="icon" type="image/ico" href="/favicon.png">
		<link rel="stylesheet" href="/css/reset.css">
		<link rel="stylesheet" href="/css/style.css">
	</head>
	<body translate="no">
		<div class="site">
			<?php
				/* relative paths are not allowed here */
				include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php");
				include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/itemview.php");
			?>
			<div class="content-wrapper">
				<div class="site-content">
					<?php showbooks();?>
				<!--
					<div id="p-float">
						<div class="p-float"><div class="p-float-in">
							<img class="p-img" alt="Bloom Into You Volume 1" src="/images/manga/bloom_into_you_1.jpg"/>
							<div class="p-name">Bloom Into You Volume 1</div>
							<div class="p-price"><span class="oldprice">$99.99</span> $79.99 20% off!</div>
							<div class="p-author"><span class="bolddesc">Author: </span>Nakatani Nio</div>
							<div class="p-type"><span class="bolddesc">Type: </span>Paperback</div>
							<div class="p-pages"><span class="bolddesc">Pages: </span>180</div>
							<div class="p-language"><span class="bolddesc">Language: </span>English</div>
							<div class="p-publisher"><span class="bolddesc">Publisher: </span>Seven Seas Entertainment</div>
							<button class="p-add">Add to Cart</button>
						</div>
					</div>
					<div class="p-float">
						<div class="p-float-in">
							<img class="p-img" src="/images/manga/bloom_into_you_2.jpg"/>
							<div class="p-name">Bloom Into You Volume 2</div>
							<div class="p-price"><span class="oldprice">$99.99</span> $79.99 20% off!</div>
							<div class="p-author"><span class="bolddesc">Author: </span>Nakatani Nio</div>
							<div class="p-type"><span class="bolddesc">Type: </span>Paperback</div>
							<div class="p-pages"><span class="bolddesc">Pages: </span>180</div>
							<div class="p-language"><span class="bolddesc">Language: </span>English</div>
							<div class="p-publisher"><span class="bolddesc">Publisher: </span>Seven Seas Entertainment</div>
							<a href="/view"><button class="p-add">View Item</button></a>
						</div>
					</div>
					<div class="p-float">
						<div class="p-float-in">
							<img class="p-img" src="/images/manga/bloom_into_you_3.jpg"/>
							<div class="p-name">Bloom Into You Volume 3</div>
							<div class="p-price">$88.88</div>
							<div class="p-author"><span class="bolddesc">Author: </span>Nakatani Nio</div>
							<div class="p-type"><span class="bolddesc">Type: </span>Paperback</div>
							<div class="p-pages"><span class="bolddesc">Pages: </span>180</div>
							<div class="p-language"><span class="bolddesc">Language: </span>English</div>
							<div class="p-publisher"><span class="bolddesc">Publisher: </span>Seven Seas Entertainment</div>
							<button class="p-add">Add to Cart</button>
						</div>
					</div>
					<div class="p-float">
						<div class="p-float-in">
							<img class="p-img" src="/images/manga/bloom_into_you_4.jpg"/>
							<div class="p-name">Bloom Into You Volume 4</div>
							<div class="p-price">$88.88</div>
							<div class="p-author"><span class="bolddesc">Author: </span>Nakatani Nio</div>
							<div class="p-type"><span class="bolddesc">Type: </span>Paperback</div>
							<div class="p-pages"><span class="bolddesc">Pages: </span>180</div>
							<div class="p-language"><span class="bolddesc">Language: </span>English</div>
							<div class="p-publisher"><span class="bolddesc">Publisher: </span>Seven Seas Entertainment</div>
							<button class="p-add">Add to Cart</button>
						</div>
					</div>
					<div class="p-float">
						<div class="p-float-in">
							<img class="p-img" src="/images/manga/bloom_into_you_5.jpg"/>
							<div class="p-name">Bloom Into You Volume 5</div>
							<div class="p-price">$88.88</div>
							<div class="p-author"><span class="bolddesc">Author: </span>Nakatani Nio</div>
							<div class="p-type"><span class="bolddesc">Type: </span>Paperback</div>
							<div class="p-pages"><span class="bolddesc">Pages: </span>180</div>
							<div class="p-language"><span class="bolddesc">Language: </span>English</div>
							<div class="p-publisher"><span class="bolddesc">Publisher: </span>Seven Seas Entertainment</div>
							<button class="p-add">Add to Cart</button>
						</div>
					</div>
					<div class="p-float">
						<div class="p-float-in">
							<img class="p-img" src="/images/manga/bloom_into_you_6.jpg"/>
							<div class="p-name">Bloom Into You Volume 6</div>
							<div class="p-price">$88.88</div>
							<div class="p-author"><span class="bolddesc">Author: </span>Nakatani Nio</div>
							<div class="p-type"><span class="bolddesc">Type: </span>Paperback</div>
							<div class="p-pages"><span class="bolddesc">Pages: </span>180</div>
							<div class="p-language"><span class="bolddesc">Language: </span>English</div>
							<div class="p-publisher"><span class="bolddesc">Publisher: </span>Seven Seas Entertainment</div>
							<button class="p-add">Add to Cart</button>
						</div>
					</div>
				-->


					</div>
				<?php
				/* relative paths are not allowed here */
				include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php");
				?>
			</div>
	</body>
</html>
