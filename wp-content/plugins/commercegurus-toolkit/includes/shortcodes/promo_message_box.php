<?php 

// [cg_promo]

function cg_hp_promo($params = array(), $content = null) {
  extract(shortcode_atts(array(
    'bg' => '#f6653c',
    'height' => '',
    'title' => 'This is a promo',
    'button_text' => '',
    'button_url' => ''
  ), $params));
  
  $bgcolor = "";
  if (strpos($bg,'#') !== false) {
    $bgcolor = 'background-color:'.$bg.'!important; ';
  }

  $height_style = "";
  if ($height) {
    $height_style = 'height:'.$height;
  }

  $content = do_shortcode($content);

  $button_content = "";
  if (isset($button_url, $button_text)) {
    $button_content = ' <a href="'.$button_url.'" class="see-through">'.$button_text.'</a>';
  } else {
    $button_content = '';
  }

  $hppromo = '   
    <div class="cg-msg-wrap highlight-block fade-in animate" style="'.$bgcolor . $height_style .'">
      <div class="cg-msg-bg"></div>
        <div class="row">
          <div class="cg-msg-text col-lg-12 col-md-12 animate" data-animate="slideInRight">
            <h2 class="cg-msg-heading">'.$title . $button_content . '</h2>
          </div>
        </div>
    </div>
  ';      
  return $hppromo;
}

add_shortcode("cg_promo", "cg_hp_promo");
