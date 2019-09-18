<?php 
  if (!isset($_REQUEST['subtopic']) || $_REQUEST['subtopic'] == 'latestnews') { 
    if ($doubleStatus = $SQL->query("SELECT `value` FROM `server_config` WHERE `config` = 'double'")->fetchAll()[0]['value'] == "active") {
      $main_content .= '
      <link href="' . $layout_name . '/js/promo/promo.css" rel="stylesheet" type="text/css">    
          <div id="promo-overlay" style="display: none"></div>
          <div id="promoloader" style="z-index: 5000; display: none">
          <div style="text-align: center">
              <a href="./?subtopic=accountmanagement&action=donate">
                  <img src="' . $layout_name . '/js/promo/widget.png">
              </a>
              </div>
          </div>
          <script src="' . $layout_name . '/js/promo/promo.js"></script>
          <script>
              // mostrar promoção quando a janela for carregada
              ' . 'window.onload = function() { showPromo(); };
              // ao clicar na promoção, redirecionar para página de donate
              var promoloader = document.getElementById("' . 'promoloader' . '");
              promoloader.onclick = function () { closePromo();  };
          </script>';      
    }
  } 
?>