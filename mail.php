<?php 
	if (mail('maksim.kuleba@gmail.com', 'Subj', 'Msg')) {
		echo 'success';
	} else {
		echo 'error';
	}
?>
