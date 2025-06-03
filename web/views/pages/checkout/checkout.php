<?php 

if(isset($_GET["numbers"])){

	$numbers = explode(",",$_GET["numbers"]);

	if(is_array($numbers)){

		if(count($numbers) == 0){

			echo "<script>
				window.location = '/';
			</script>";

			return;

		}else{

			include "modules/hero/hero.php";
			include "modules/main/main.php";
			include "modules/privacy/privacy.php";
		}

	}else{

		echo "<script>
			window.location = '/';
		</script>";

		return;
	}
	

}else{

	echo "<script>
		window.location = '/';
	</script>";

	return;
}


	
?>

<script src="/views/assets/js/forms/forms.js"></script>