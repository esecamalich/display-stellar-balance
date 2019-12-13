// This will be a list of all Public Keys
var keysArray = [];

// Find all elements with class="stellarWallet"
var wallet = document.getElementsByClassName("stellarWallet");
var key;

// Get the id of all elements with class="stellarWallet" and
// add it to keysArray
function getKeys() {
  for (key = 0; key < wallet.length; key++) {
    keysArray.push(wallet[key].id)
  }
}

getKeys();

var request = new XMLHttpRequest();

(function loop(i, length) {

    if (i>= length) {
        return;
    }


    // Get info of specific Stellar account based on id
    var url = "https://horizon.stellar.org/accounts/" + keysArray[i];

    request.open("GET", url);

    request.onreadystatechange = function() {
        if(request.readyState === XMLHttpRequest.DONE && request.status === 200) {
            var data = JSON.parse(request.responseText);

            // Get the balance data of last item in 'balances' and remove all digits after the dot
            var balance = Number(data.balances.slice(-1)[0].balance).toFixed(0);

            // Add balance to the specified element
            document.getElementById(keysArray[i]).textContent = balance + " XLM";

            loop(i + 1, length);
        }
    }

    request.send();
    
})(0, keysArray.length);
