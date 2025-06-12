function fabChat() {
	// FabChat click target
	const fabchat = document.querySelector( '.block.chat-form' );
	const fabchatToggle = document.getElementById( 'fabchat' );

	// Check exists
	if ( fabchat && fabchatToggle ) {
		// Add listeners for mobile and desktop
		// fabchat.addEventListener( 'touchstart', toggleChat );
		fabchatToggle.addEventListener( 'click', toggleChat );

		// Set up empty anchor for when form has been submitted
		let anchor = false;
		// Grab url
		const url = window.location.href;
		// Explode into array on forward slash
		const urlArr = url.split( '/' );
		// Get last array entry which should always be a our anchor
		anchor = urlArr[ urlArr.length - 1 ];
		// Empty element
		let thankYouEl = null;
		// Check to prevent DOM Exception
		if ( anchor.includes( '#thank-you' ) ) {
			thankYouEl = document.querySelector( anchor );
		}
		// Trigger fabchat if thank you is present and hide again after 3 seconds
		if ( thankYouEl ) {
			toggleChat();
			setTimeout( () => {
				toggleChat();
			}, 3000 );
		}
	}

	function toggleChat() {
		fabchat.classList.toggle( 'open' );
	}
}

export default fabChat;
