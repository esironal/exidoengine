<html>
<head>
  <?php
  $helper->base()
         ->title('Component Special page')
         ->charset()
         ->fav('exidoengine')
         ->css('basic')
         ->css('reset')
         ->css('tools')
         ->css('style')
         ->ie('ie-hack');
  ?>
</head>
<body>
<?php print $view->getActionContent(); ?>
</body>
</html>