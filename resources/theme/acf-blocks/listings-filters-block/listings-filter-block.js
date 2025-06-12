import noUiSlider from 'nouislider';
import 'nouislider/dist/nouislider.css';

function checkListings() {
	const listingsblock = document.querySelector( '.block.listings' );

	// Listings block not found, abort
	if ( ! listingsblock ) {
		return;
	}

	// trigger page change
	$( '.block.listings .results #pagination-output' ).on( 'click', '.page-numbers', function() {
		if ( ! $( this ).hasClass( 'current' ) ) {
			$( 'input[name=target_page]' ).val( $( this ).data( 'page' ) );
			listingsAJAXPost();
			$( this ).get(0).closest('.listings').scrollIntoView();
		}
	} );

	$( 'form.filters-form' ).submit( function( e ) {
		e.preventDefault();
		listingsAJAXPost();
	} );

	function listingsAJAXPost() {
		$( '.block.listings .results #listings-output' ).fadeTo( 'fast', 0 );
		$( '.block.listings .results #loader-holder' ).fadeTo( 'fast', 0.58 );

		const data = $( '.filters-form' ).serialize();
		$.post( $( 'input[name=post_url]' ).val(), data, function( response ) {
			$( '.block.listings .results #listings-output' ).empty();
			if ( response.data == 'no_results' ) {
				$( '.block.listings .results #loader-holder' ).fadeOut( 'fast' );
			} else {
				$( '.block.listings .results #listings-output' ).append( response.data.cards );
				$( '.block.listings .results #pagination-output' ).html( response.data.paginator );
				$( '.block.listings .results #no-results' ).fadeOut( 'fast' );
				$( '.block.listings .results #listings-output' ).fadeTo( 'fast', 1 );
			}
		} ).always(function() {
			$( '.block.listings .results #loader-holder' ).fadeOut( 'fast' );
		});
	}
	listingsAJAXPost();
}

function listingsFiltersBlock() {
	checkListings();
	const filtersToggle = document.querySelector( '.block.listings .filters-toggle' );
	const filtersCol = document.querySelector( '.block.listings .filters-col' );
	const closeBtn = document.querySelector( '.block.listings #close-filters' );
	const closeFilter = document.querySelector('.block.listings #show-results');

	if ( ! filtersToggle || ! filtersCol ) {
		return;
	}

	filtersToggle.addEventListener( 'click', function() {
		filtersToggle.classList.toggle( 'toggled' );
		filtersCol.classList.toggle( 'toggled' );
	} );

	function closeFilters() {
		filtersToggle.classList.remove('toggled');
		filtersCol.classList.remove('toggled');
	}
	
	if (closeBtn) {
		closeBtn.addEventListener('click', closeFilters);
	}
	
	if (closeFilter) {
		closeFilter.addEventListener('click', closeFilters);
	}

	// Price Range Slider
	const priceSlider = document.getElementById( 'price-slider' );
	const priceValues = document.getElementById( 'price-values' );
	const priceMinInput = document.getElementById( 'price_min' );
	const priceMaxInput = document.getElementById( 'price_max' );
	const [ priceMinInt, priceMaxInt ]
		= ( priceMaxInput && priceMinInput )
			? [ parseInt( priceMinInput.value ), parseInt( priceMaxInput.value ) ]
			: [ 0, 25000 ];

	// Init Slider
	noUiSlider.create( priceSlider, {
		start: [ priceMinInt, priceMaxInt ],
		connect: true,
		step: 100,
		range: {
			min: priceMinInt,
			max: priceMaxInt,
		},
		format: {
			to: (value) => parseInt( value ),
			from: (value) => parseInt( value )
		  }
	} );

	// update hidden form value
	priceSlider.noUiSlider.on( 'change', function() {
		const [ minPrice, maxPrice ] = this.get();
		document.getElementById( 'price_min' ).value = minPrice;
		document.getElementById( 'price_max' ).value = maxPrice;
	} );

	// Update values on frontend
	priceSlider.noUiSlider.on( 'update', function( values ) {
		priceValues.innerHTML = values.join( ' - £' );
	} );

	// Get values on load
	// console.log( priceSlider.noUiSlider.get() );

	// Reset
	const resetBtn = document.getElementById( 'reset-filters' );
	const checks = document.querySelectorAll( '.categories .btn-check' );

	if ( resetBtn ) {
		resetBtn.addEventListener( 'click', function() {
			priceSlider.noUiSlider.reset();
			priceMinInput.value = 0;
			priceMaxInput.value = 0;
			checks.forEach( function( check ) {
				check.checked = false;
			} );
		} );
	}
}

export default listingsFiltersBlock;
