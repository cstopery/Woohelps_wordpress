<?php

show_admin_bar(false);



// Added footer credits
function woohelps_footer_credits() {
	echo '<span><a rel="nofollow" href="http://www.hardeepasrani.com/portfolio/docpress/">MartinSun</a> - '.__('Proudly powered by','MartinSun').' MartinSun</span>';
}
add_action( 'woohelps_credits', 'woohelps_footer_credits' );


?>