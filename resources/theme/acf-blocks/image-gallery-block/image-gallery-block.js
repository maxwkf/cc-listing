// import Swiper JS
import Swiper, { Lazy, Pagination } from 'swiper';
import { mediaQueryDown } from '../../src/js/global/global';
// import Swiper styles
import '../../node_modules/swiper/swiper.scss';
import '../../node_modules/swiper/modules/pagination/pagination.scss';

let init = false;
let swiper;

function imageGalleryBlock() {
    const md = 991;
    
    if ( mediaQueryDown( md ) ) {
        console.log('media query down fired');
        if (!init) {
            init = true;
            swiper = new Swiper( '.rental-carousel-mobile', {
                modules: [ Pagination ],
                // Disable preloading of all images
                preloadImages: false,
                pagination: {
                    el: '.swiper-pagination',
                    type: 'fraction',
                },
                slidesPerView: 1,
                initialSlide: 1,
            } );
        }
    } else if (init) {
        console.log('media query up fired');

      swiper.destroy();
      init = false;
    }
}

export default imageGalleryBlock;