document.querySelectorAll('nav a')
  .forEach(e => e.addEventListener('click', _ => change(e.dataset.id)))


function change(n) {
  let panels = document.querySelectorAll('main div')
  panels.forEach(p => p.classList.remove('active'))
  panels[n - 1].classList.add('active')
}


$(document).ready(function(){

	fetch_data();

	function fetch_data()
	{
		$.ajax({
			url:"fetch.php",
			success:function(data)
			{
				$('tbody').html(data);




                var userSelection = document.getElementsByClassName('shop-item-button');

                for(var i=0; i < userSelection.length; i++) {
                    (function(index) {
                        userSelection[index].addEventListener("click", addToCartClicked)
                    })(i);
                }




            
			}
		})
	}

	$('#add_button').click(function(){
		$('#action').val('insert');
		$('#button_action').val('Insert');
		$('.modal-title').text('Add Data');
		$('#apicrudModal').modal('show');
	});

	$('#api_crud_form').on('submit', function(event){
		event.preventDefault();
		if($('#RoomPic').val() == '')
		{
			alert("Enter RoomPic");
		}
		else if($('#RoomDes').val() == '')
		{
			alert("Enter RoomDes");
		}
              else if($('#RoomPrice').val() == '')
		{
			alert("Enter RoomPrice");
		}
              else if($('#RoomNumber').val() == '')
		{
			alert("Enter RoomNumber");
		}
		else
		{
			var form_data = $(this).serialize();
			$.ajax({
				url:"action.php",
				method:"POST",
				data:form_data,
				success:function(data)
				{
					fetch_data();
					$('#api_crud_form')[0].reset();
					$('#apicrudModal').modal('hide');
					if(data == 'insert')
					{
						alert("Data Inserted using PHP API");
					}
					if(data == 'update')
					{
						alert("Data Updated using PHP API");
					}
				}
			});
		}
	});

	$(document).on('click', '.edit', function(){
		var id  = $(this).attr('id');
		var action = 'fetch_single';
		$.ajax({
			url:"action.php",
			method:"POST",
			data:{id:id, action:action},
			dataType:"json",
			success:function(data)
			{

				$('#hidden_id').val(id);
				$('#RoomPic').val(data.RoomPic);
				$('#RoomDes').val(data.RoomDes);
                            $('#RoomPrice').val(data.RoomPrice);
				$('#RoomNumber').val(data.RoomNumber);
				$('#action').val('update');
				$('#button_action').val('Update');
				$('.modal-title').text('Edit Data');
				$('#apicrudModal').modal('show');
			}
		})
	});

	$(document).on('click', '.delete', function(){
		var id = $(this).attr("id");
		var action = 'delete';
		if(confirm("Are you sure you want to remove this data using PHP API?"))
		{
			$.ajax({
				url:"action.php",
				method:"POST",
				data:{id:id, action:action},
				success:function(data)
				{
					fetch_data();
					alert("Data Deleted using PHP API");
				}
			});
		}
	});


//    document.getElementsByClassName("shop-item-button").addEventListener('click', addToCartClicked;

    

});


document.getElementById('loginform').addEventListener('submit', function(e) {fetchlogin(e)});
document.getElementById('registerform').addEventListener('submit', function(e) {fetchregister(e)});

document.getElementById('orderregform').addEventListener('submit', function(e) {fetchorderreg(e)});

//document.getElementById('roomregform').addEventListener('submit', function(e) {fetchroomreg(e)});

document.getElementById('accountexists').addEventListener('input', function(e) {fetchaccountexists(e)});
document.getElementById('linkisloggedin').addEventListener('click', function(e) {fetchisloggedin(e)});
document.getElementById('logoutbutton').addEventListener('click', function(e) {fetchlogout(e)});

// document.getElementById('roomeditbutton').addEventListener('click', function(e) {fetcheditroom(e)});
//document.getElementById('roomdisplaybutton').addEventListener('click', function(e) {fetchroomdisplay(e)});
//document.getElementById('button1').innerHTML=fetchroomdisplay();


function fetchlogin(evt) {           // log in function for both customers and admins
    evt.preventDefault()
    var fd = new FormData();
    fd.append('Username', loginuser.value);
    fd.append('Pass', loginpass.value);
    fd.append('Rol', loginrol.value);
    
    fetch('http://localhost/hotel-0420/api/api.php?action=login', 
    {
        method: 'POST',
        body: fd,
        credentials: 'include'
    })
    .then(function(headers) {
        if(headers.status == 401) {
            console.log('login failed');
            localStorage.removeItem('csrf');
            localStorage.removeItem('Username');
            localStorage.removeItem('Rol');            
            localStorage.removeItem('Phone');
            localStorage.removeItem('Email');
            localStorage.removeItem('CustomerID');
            return;
        }
        if(headers.status == 203) {
            console.log('registration required');
            // only need csrf
        }
        headers.json().then(function(body) {
            // BUG is this a 203 or 200?
            localStorage.setItem('csrf', body.Hash);
            localStorage.setItem('CustomerID',loginuser.value);
            localStorage.setItem('Username', body.Username);
            localStorage.setItem('Rol', body.Rol);            
            localStorage.setItem('Email', body.Email);
            localStorage.setItem('Phone', body.Phone);
        })
    })
    .catch(function(error) {
        console.log(error)
    });
}
function fetchregister(evt) {     // register function for both customers and admins
    evt.preventDefault();
    var fd = new FormData();
    fd.append('Username', regUsername.value);
    fd.append('Rol', regRol.value);
    fd.append('Email', regEmail.value); //lop off # in hex code
    fd.append('Phone', regPhone.value);
    fd.append('Pass', regPass.value);
//    fd.append('csrf', localStorage.getItem('csrf'));
    fetch('http://localhost/hotel-0420/api/api.php?action=register', 
    {
        method: 'POST',
        body: fd,
        credentials: 'include'
    })
    .then(function(headers) {
        if(headers.status == 400) {
            console.log('register failed');
            return;
        }
        if(headers.status == 201) {
            console.log('registration updated');
            return;
        }
    })
    .catch(error => console.log(error));
}



function fetchaccountexists(evt) {       // To check if account exists
    if(evt.srcElement.value.length > 3) {
        fetch('http://localhost/hotel-0420/api/api.php?action=accountexists&username='+ evt.srcElement.value, 
        {
            method: 'GET',
            credentials: 'include'
        })
        .then(function(headers) {
            if(headers.status == 204) {
                console.log('user does not exist');
                return;
            }
            if(headers.status == 400) {
                console.log('user exists');
                return;
            }
            headers.json().then(function(body) {
                console.log(body);
            })
        })
        .catch(error => console.log(error));
    }
}
function fetchisloggedin(evt) {       // To check if account logs in
    fetch('http://localhost/hotel-0420/api/api.php?action=isloggedin', 
    {
        method: 'GET',
        credentials: 'include'
    })
    .then(function(headers) {
        if(headers.status == 403) {
            console.log('not logged in');
            localStorage.removeItem('csrf');
            localStorage.removeItem('Username');
            localStorage.removeItem('Rol');
            localStorage.removeItem('Email');
            localStorage.removeItem('Phone');
            localStorage.removeItem('CustomerID');
            return;
        }
        headers.json().then(function(body) {
            localStorage.setItem('Username', body.Username);
            localStorage.setItem('csrf', body.Hash);
        })
    })
    .catch(error => console.log(error));
}
function fetchlogout(evt) {            // Function for log out
    fetch('http://localhost/hotel-0420/api/api.php?action=logout', 
    {
        method: 'GET',
        credentials: 'include'
    })
    .then(function(headers) {
        if(headers.status != 200) {
            console.log('logout failed Server-Side, but make client login again');
        }
        localStorage.removeItem('csrf');
        localStorage.removeItem('Username');
        localStorage.removeItem('Rol');
        localStorage.removeItem('Email');
        localStorage.removeItem('Phone');
        localStorage.removeItem('CustomerID');    
    })
    .catch(error => console.log(error));
}


function fetchorderreg(evt) {                // function for making an order
    evt.preventDefault();
    var fd = new FormData();
    fd.append('CustomerID', regorderCustomerID.value); //lop off # in hex code
    fd.append('id', regorderid.value);
    fd.append('DateStart', regDateStart.value);
    fd.append('DateFinish', regDateFinish.value);
    fd.append('OrderStatus', regOrderStatus.value);
    fd.append('TotalAmount', regTotalAmount.value);
    fetch('http://localhost/hotel-0420/api/api.php?action=orderregister', 
    {
        method: 'POST',
        body: fd,
        credentials: 'include'
    })
    .then(function(headers) {
        if(headers.status == 400) {
            console.log('Order register failed');
            return;
        }
        if(headers.status == 201) {
            console.log('Order registration updated');
            return;
        }
    })
    .catch(error => console.log(error));
}




function fetchorderedit(evt) {                    // function to edit an order
    evt.preventDefault();
    var fd = new FormData();
    fd.append('CustomerID', regorderCustomerID.value); 
    fd.append('id', regorderid.value);
    fd.append('DateStart', regDateStart.value);
    fd.append('DateFinish', regDateFinish.value);
    fd.append('OrderStatus', regOrderStatus.value);
    fd.append('TotalAmount', regTotalAmount.value);
    fetch('http://localhost/hotel-0420/api/api.php?action=orderedit', 
    {
        method: 'POST',
        body: fd,
        credentials: 'include'
    })
    .then(function(headers) {
        if(headers.status == 400) {
            console.log('Order Edit failed');
            return;
        }
        if(headers.status == 201) {
            console.log('Order Edit updated');
            return;
        }
    })
    .catch(error => console.log(error));
}



function fetchorderdelete(evt) {                   // Function to delete an order   
    evt.preventDefault();
    var fd = new FormData();
    fd.append('CustomerID', regorderCustomerID.value); 
    fd.append('id', regorderid.value);
    fd.append('DateStart', regDateStart.value);
    fd.append('DateFinish', regDateFinish.value);
    fd.append('OrderStatus', regOrderStatus.value);
    fd.append('TotalAmount', regTotalAmount.value);
    fetch('http://localhost/hotel-0420/api/api.php?action=orderDelete', 
    {
        method: 'DELETE',
        body: fd,
        credentials: 'include'
    })
    .then(function(headers) {
        if(headers.status == 400) {
            console.log('Order Edit failed');
            return;
        }
        if(headers.status == 201) {
            console.log('Order Edit updated');
            return;
        }
    })
    .catch(error => console.log(error));
}



function fetchroomdisplay() {                    // function to display all rooms
 
    fetch('http://localhost/hotel-0420/api/api.php?action=roomdisplay', 
    {
        method: 'POST',
        credentials: 'include'
    })
    .then((res) => res.json()) 
    .then(response => {
        console.log(response);
        let output = '';
        for(let i in response){
            output += `<tr>
            <td type="number">${response[i].id}</td>
            <td> <img src='../images/${response[i].RoomPic}' style="width: 100px;eight:100px;"> </td>
            <td type="text">${response[i].RoomDes}</td>
            <td type="number">${response[i].RoomPrice}</td>
            <td type="number">${response[i].RoomNumber}</td>
			<td><button type="button" name="edit" class="btn btn-warning btn-xs edit" id="'.$row->id.'">Edit</button></td>
			<td><button type="button" name="delete" class="btn btn-danger btn-xs delete" id="'.$row->id.'">Delete</button></td>
            </tr>`;
        }
     
        document.querySelector('.tbody').innerHTML = output;
        document.getElementsByClassName('roomeditbutton').foreach((button) => {
            button.addEventListener('click', function(e) {fetcheditroom(e)});
        })
            
    }).catch(error => console.log(error));
}
//            <td><button class="roomeditbutton" data-id="${response[i]}">Add</button></td>



if (document.readyState == 'loading') {
    document.addEventListener('DOMContentLoaded', ready)
} else {
    ready()
}

function ready() {


    var removeCartItemButtons = document.getElementsByClassName('btn-danger')
    for (var i = 0; i < removeCartItemButtons.length; i++) {
        var button = removeCartItemButtons[i]
        button.addEventListener('click', removeCartItem)
    }

    var quantityInputs = document.getElementsByClassName('cart-quantity-input')
    for (var i = 0; i < quantityInputs.length; i++) {
        var input = quantityInputs[i]
        input.addEventListener('change', quantityChanged)
    }

    var addToCartButtons = document.getElementsByClassName('shop-item-button')
    for (var i = 0; i < addToCartButtons.length; i++) {
        var button = addToCartButtons[i]
        button.addEventListener('click', addToCartClicked)
        
    }

    document.getElementsByClassName('btn-purchase')[0].addEventListener('click', purchaseClicked)
}

function purchaseClicked() {
    alert('Thank you for your purchase')
    var cartItems = document.getElementsByClassName('cart-items')[0]
    while (cartItems.hasChildNodes()) {
        cartItems.removeChild(cartItems.firstChild)
    }
    updateCartTotal()
}

function removeCartItem(event) {
    var buttonClicked = event.target
    buttonClicked.parentElement.parentElement.remove()
    updateCartTotal()
}

function quantityChanged(event) {
    var input = event.target
    if (isNaN(input.value) || input.value <= 0) {
        input.value = 1
    }
    updateCartTotal()
}

function addToCartClicked(event) {

    var button = event.target
    var shopItem = button.parentElement.parentElement
    console.log(shopItem)

    var title = shopItem.getElementsByClassName('shop-item-title')[0].innerText
    var price = shopItem.getElementsByClassName('shop-item-price')[0].innerText
//    var imageSrc = shopItem.getElementsByClassName('shop-item-image')[0].src
    var imageSrc = shopItem.getElementsByClassName('shop-item-image')[0].innerText

    addItemToCart(title, price, imageSrc)
    updateCartTotal()

}

function addItemToCart(title, price, imageSrc) {

    console.log(title)
    console.log(price)
    console.log(imageSrc)

    var cartRow = document.createElement('div')
    cartRow.classList.add('cart-row')
    var cartItems = document.getElementsByClassName('cart-items')[0]
    var cartItemNames = cartItems.getElementsByClassName('cart-item-title')
    for (var i = 0; i < cartItemNames.length; i++) {
        if (cartItemNames[i].innerText == title) {
            alert('This item is already added to the cart')
            return
        }
    }
    var cartRowContents = `
        <div class="cart-item cart-column">
            <img class="cart-item-image" src="${imageSrc}" width="100" height="100">
            <span class="cart-item-title">${title}</span>
        </div>
        <span class="cart-price cart-column">${price}</span>
        <div class="cart-quantity cart-column">
            <input class="cart-quantity-input" type="number" value="1">
            <button class="btn btn-danger" type="button">REMOVE</button>
        </div>`
    cartRow.innerHTML = cartRowContents
    cartItems.append(cartRow)
    cartRow.getElementsByClassName('btn-danger')[0].addEventListener('click', removeCartItem)
    cartRow.getElementsByClassName('cart-quantity-input')[0].addEventListener('change', quantityChanged)

}

function updateCartTotal() {
    var cartItemContainer = document.getElementsByClassName('cart-items')[0]
    var cartRows = cartItemContainer.getElementsByClassName('cart-row')
    var total = 0
    for (var i = 0; i < cartRows.length; i++) {
        var cartRow = cartRows[i]
        var priceElement = cartRow.getElementsByClassName('cart-price')[0]
        var quantityElement = cartRow.getElementsByClassName('cart-quantity-input')[0]
        var price = parseFloat(priceElement.innerText.replace('$', ''))
        var quantity = quantityElement.value
        total = total + (price * quantity)
    }
    total = Math.round(total * 100) / 100
    document.getElementsByClassName('cart-total-price')[0].innerText = '$' + total
}


var darkSwitch = document.getElementById("darkSwitch");
window.addEventListener("load", function () {
  if (darkSwitch) {
    initTheme();
    darkSwitch.addEventListener("change", function () {
      resetTheme();
    });
  }
});

/**
 * Summary: function that adds or removes the attribute 'data-theme' depending if
 * the switch is 'on' or 'off'.
 *
 * Description: initTheme is a function that uses localStorage from JavaScript DOM,
 * to store the value of the HTML switch. If the switch was already switched to
 * 'on' it will set an HTML attribute to the body named: 'data-theme' to a 'dark'
 * value. If it is the first time opening the page, or if the switch was off the
 * 'data-theme' attribute will not be set.
 * @return {void}
 */
function initTheme() {
  var darkThemeSelected =
    localStorage.getItem("darkSwitch") !== null &&
    localStorage.getItem("darkSwitch") === "dark";
  darkSwitch.checked = darkThemeSelected;
  darkThemeSelected
    ? document.body.setAttribute("data-theme", "dark")
    : document.body.removeAttribute("data-theme");
}

/**
 * Summary: resetTheme checks if the switch is 'on' or 'off' and if it is toggled
 * on it will set the HTML attribute 'data-theme' to dark so the dark-theme CSS is
 * applied.
 * @return {void}
 */
function resetTheme() {
  if (darkSwitch.checked) {
    document.body.setAttribute("data-theme", "dark");
    localStorage.setItem("darkSwitch", "dark");
  } else {
    document.body.removeAttribute("data-theme");
    localStorage.removeItem("darkSwitch");
  }
}



function initTheme(){
    var e=null!==localStorage.getItem("darkSwitch")&&"dark"===localStorage.getItem("darkSwitch");
    darkSwitch.checked=e,e?document.body.setAttribute("data-theme","dark"):document.body.removeAttribute("data-theme")}
    
function resetTheme(){
    darkSwitch.checked?(document.body.setAttribute("data-theme","dark"),localStorage.setItem("darkSwitch","dark")):(document.body.removeAttribute("data-theme"),localStorage.removeItem("darkSwitch"))}
    
    var darkSwitch=document.getElementById("darkSwitch");window.addEventListener("load",function(){darkSwitch&&(initTheme(),darkSwitch.addEventListener("change",function(){resetTheme()}))});
