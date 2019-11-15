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

class Cart
{
	/* 
	 * cart class
	 */


	private $bookCount = 0;
	private $books = Array();

	/* constructor */
	public function __construct()
	{
	}

	public function __destruct()
	{

	}

	public function getBooks()
	{
		return $this->books;
	}

	public function removeBook($isbn)
	{
		if (array_key_exists($isbn, $this->books))
		{
			unset($this->books[$isbn]);
		}
	}

	public function modifyBook($isbn, $count)
	{
		if (!is_numeric($count))
			return;
		if (array_key_exists($isbn, $this->books))
		{
			$this->books[$isbn] = $count;
			if($this->books[$isbn] <= 0)
			{
				/* nice try */
				unset($this->books[$isbn]);
			}
		}
		else
		{

			$this->books += array($isbn=>$count);
		}

	}

	public function addBook($isbn, $count)
	{
		/*
		 * add a book
		 * we expect the book has already been verified to exit
		 * TODO: make verification class method?
		 */
		if (!is_numeric($count))
			return;
		if (array_key_exists($isbn, $this->books))
		{
			$this->books[$isbn]+=$count;
			if($this->books[$isbn] <= 0)
			{
				/* nice try */
				unset($this->books[$isbn]);
			}
		}
		else
		{

			$this->books += array($isbn=>$count);
		}
	}

	public function printBooks()
	{
		foreach($this->books as $isbn=>$count)
		{
			echo "isbn=" . $isbn . ", count=" . $count;
			echo "<br>";
		}
	}
	
	public function emptyCart()
	{
		foreach($this->books as $isbn=>$count)
		{
			unset($this->books[$isbn]);
		}
	}

}

?>
