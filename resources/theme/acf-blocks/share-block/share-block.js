
function ShareIcon() {
    var copyButton = document.querySelector('.copy button');
    var copyInput = document.querySelector('.copy input');

    if(copyButton) {
    copyButton.addEventListener('click', function(e) {
      e.preventDefault();
      var text = copyInput.select();
      document.execCommand('copy');
    });
    }

    if(copyInput){
    copyInput.addEventListener('click', function() {
      this.select();
    });
  }
}

export default ShareIcon;