function listingSummaryBlock() {
    let lastScrollHeight = 0;
    window.addEventListener( 'scroll', function(){
        const stickyWrap = document.getElementById('sticky-wrap');

        if ( stickyWrap ) {
			if ( window.scrollY >= lastScrollHeight ) {
				stickyWrap.classList.add( 'shrink' );
			} else {
				stickyWrap.classList.remove( 'shrink' );
			}
            lastScrollHeight=window.scrollY;
		}
    } );
}

export default listingSummaryBlock;