<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('alkitab_dev', 'Alkitab Web Service');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Budi Susanto (budsus@ti.ukdw.ac.id)">
		<title><?php echo $cakeDescription; ?></title>
		<?php
			echo $this->Html->meta('icon');
			echo $this->Html->css(array('bootstrap.min', 'sticky-footer'));
		?>
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
<body>
  <!-- Begin page content -->
  <div class="container">
    <div class="page-header">
      <h1>Prototype Alkitab Web Service</h1>
    </div>
		<?php echo $this->Flash->render(); ?>

		<?php echo $this->fetch('content'); ?>
  </div>

  <footer class="footer">
    <div class="container">
      <p class="text-muted">Web Service Alkitab (Prototype version)</p>
    </div>
  </footer>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<?php echo $this->Html->script(array('bootstrap.min')); ?>
	<script type="text/javascript">
	$(document).ready(function(){
		<?php echo $this->element('reqalkitab'); ?>
	});
	</script>
</body>
</html>
