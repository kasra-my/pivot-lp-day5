<?php
   require_once('includes/header.php');
   require_once('Validation.php');
   require_once('GravityAssist.php');

?>
		<div class="container">
		  <div class="row">
			<h1>Code challeng - Pivot Professional Learning</h1>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
		  </div>
		</div>


		<div class="container">

				<form action="" method="POST">
				  <div class="form-group">
				  	<div class="col-xs-3">
					    <label for="intcode">Please enter the Intcode program: </label>
						<?php isset($_POST['intcode'])? $placeHolder = $_POST['intcode'] : $placeHolder = 'Enter Intcode Program' ?>
					    <input type="text" class="form-control" name="intcode" id="intcode" placeholder='<?php echo 'Your last input: ' . $placeHolder; ?>'>
					</div>
				  </div>

				  <button type="submit" class="btn btn-primary">Submit</button>
				</form>


		</div>
		<br>
		<br>
		<br>
		<div class="container">
			<?php

				if(isset($_POST['intcode'])){ 
				  	$userInput        = $_POST['intcode']; 

					$validator        = new Validation($userInput);
					$validationErrors = $validator->validate();
					
					if (empty($validationErrors)){
						$gravityAssist = new GravityAssist($userInput);
						$gravityAssist->process();

						echo '<div style="background-color:#0CA73A; padding: 5px; font-weight:bold;">' . Validation::INVALID_OPERATTIONS . '</div><br><br>';
						echo '<div style="color:blue; padding: 5px; font-weight:bold;">The new result is: ' . str_replace('"', "", $gravityAssist->report());


					} else {
						// Display all error messages here:
						echo "<ul>";

						foreach ($validationErrors as $key => $error) {
							echo '<li class="list-group-item list-group-item-danger">';
							echo $error;
							echo '</li>';
						}

						echo "</ul>";
					}

				}  

			?>

		</div>









<?php
require_once('includes/footer.php');

?>