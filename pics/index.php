<html>
	<head>
		<title>Army Of Jon - Members Gallery</title>
		<meta name="description" content="Army of Jon" />

		<link  href="http://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.2/fotorama.css" rel="stylesheet"> <!-- 3 KB -->
	</head>
	<body>
		<div class="fotorama" data-width="100%" data-ratio="800/600" style="display: none">
			<?
			// populate the div with all the image gallery images...
			$dir = dir(".");
			while (false !== ($entry = $dir->read())) {
			   if (substr($entry,0,1) != '.' )
			   {
			   	echo '<img src="'.$entry.'">';
			   }
			}
			?>
		</div>

		<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
		<script src="http://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.2/fotorama.js"></script>
	</body>

	<script>
	$(document).ready()
	{
		$('.fotorama').show();
	}
	</script>
</html>