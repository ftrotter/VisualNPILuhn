<?php

	if(!isset($_GET['npi'])){
		echo "I really do not work without a 10 digit numeric NPI";
		exit();
	}

	if(strlen($_GET['npi']) != 10){
		echo "It really does need to be 10 digits";
		exit();
	}

	if(!is_numeric($_GET['npi'])){
		echo "An NPI must be a number";
		exit();
	}

	$npi = $_GET['npi'];


$html = '<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

    <title>Calculate Luhn From NPI</title>
  </head>
  <body>
<main role="main" class="container">

  <div class="starter-template">
    <h1>NPI Luhn Calculation for '.$npi.'</h1>
';

	echo $html;

	echo "<table class='table table-bordered' >
<thead>
<tr>
<th colspan='5'> NCITS card prefix</th>
<th colspan='9'> NPI Sequence </th>
<th> Check Digit </th>
</tr>
</thead>
<tbody>
<tr>";

	$prefix = "80840"; // prefix from https://www.cms.gov/Regulations-and-Guidance/Administrative-Simplification/NationalProvIdentStand/Downloads/NPIcheckdigit.pdf

	$npi_array = str_split("$prefix$npi");

	foreach($npi_array as $i => $this_digit){
		$class = '';
		if($i<14){
			echo "<td $class>$this_digit</td>";
		}else{
			echo "<td>?</td>";
			$input_check_digit = $this_digit;
		}
	}

	echo "</tr><tr>";

	$new_array = [];

	foreach($npi_array as $i => $this_digit){
		if($i%2){ //then this is an even digit...
			$real_digit = $this_digit * 2;
			$class = "class='table-success'";
		}else{
			$real_digit = $this_digit;
			$class = "";
		}

		$new_array[$i] = $real_digit;
		
		if($i<14){
			echo "<td $class>$real_digit</td>";
		}else{
			echo "<td>?</td>";
		}
	}

	echo "</tr><tr>";

	$last_array = [];

	foreach($new_array as $i => $this_digit){

		if($this_digit > 9){
			$remainder = $this_digit - 10;
			$new_digit = 1 + $remainder;
			$class = "class='table-warning'";	
		}else{
			$new_digit = $this_digit;
			$class = "";
		}

		$last_array[$i] = $new_digit;
		
		if($i<14){
			echo "<td $class>$new_digit</td>";
		}else{
			echo "<td>?</td>";
		}

	}

	echo "</tr><tr>";


	$running_total = 0;

	foreach($last_array as $i => $this_digit){

		$class = "";

		if($i<14){
			$cell_contents = "$running_total+$this_digit=";
			$running_total += $this_digit;
		}else{
			$cell_contents = '';
		}
		$cell_contents .= "$running_total";

		echo "<td>$cell_contents</td>";
	}

	echo "</tr>
</tbody>
</table>
";

	$mult_result = 9 * $running_total;
	$mod_result = $mult_result % 10;
	
	if($input_check_digit == $mod_result){
		$result = "Luhn Number Pass. check digit passed in was $input_check_digit final result was $mod_result. This could be a valid NPI";
	}else{
		$result = "Luhn Number Fail. check digit was $input_check_digit and result was $mod_result. Not a valid NPI.";
	}


echo "
<ul class='list-group'>
  <li class='list-group-item'>Step 0: Append the NCITS code to the first 9 digits of the NPI candidate. </li> 
  <li class='list-group-item list-group-item-success'>Step 1: multiple every even cell (starting from the right) by two </li>
  <li class='list-group-item list-group-item-warning'>Step 2: for results greater then ten, add the two digits so 18 becomes 1+8 = 9 </li>
  <li class='list-group-item'>Step 3: Add up all of the numbers</li>
  <li class='list-group-item'>Step 4: Multiple this number by nine. Here: $running_total x 9 = $mult_result</li>
  <li class='list-group-item'>Step 5: Take this result, and apply modulus 10. $mult_result modulus 10 = <b>$mod_result<b></li>
  <li class='list-group-item'>Result $result</li>
</ul>
";	


$html_end = '
  </div>
</main>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
  </body>
</html>
';
	
echo $html_end;
