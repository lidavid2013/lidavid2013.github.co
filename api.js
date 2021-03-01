document.querySelectorAll('nav a')
  .forEach(e => e.addEventListener('click', _ => change(e.dataset.id)))


function change(n) {
  let panels = document.querySelectorAll('main div')
  panels.forEach(p => p.classList.remove('active'))
  panels[n - 1].classList.add('active')
}


document.getElementById('loginform').addEventListener('submit',function(e) {fetchlogin(e)});
document.getElementById('registerform').addEventListener('submit',function(e) {fetchregister(e)});
document.getElementById('accountexists').addEventListener('input',function(e) {fetchaccountexists(e)});
document.getElementById('linkisloggedin').addEventListener('click',function(e) {fetchisloggedin(e)});
document.getElementById('logoutbutton').addEventListener('click',function(e) {fetchlogout(e)});

function fetchlogin(evt) {
    evt.preventDefault()
    var fd=new FormData();
    fd.append('username',loginuser.value);
    fd.append('password',loginpass.value);
    fetch('http://localhost:9998/api.php?action=login',
    {
        method: 'POST',
        body: fd,
        credentials:'include'
    })
    .then(function(headers) {
        console.log(headers)
        headers.json().then(function(body) {
            console.log(body);
        })
    })
    .catch(function(error) {
        console.log(error)
    });
}

function fetchregister(evt) {
    evt.preventDefault();
    var fd=new FormData();
    fd.append('username',loginuser.value);
    fd.append('password',loginpass.value);
    fd.append('email',regemail.value);        
    fd.append('phone',regphone.value);
    fetch('http://localhost:9998/api.php?action=register',
    {
        method: 'POST',
        body: fd,
        credentials: 'include'
    })      
    .then(headers=>console.log(headers),
        headers.json().then(body=>console.log(body)))
    .catch(error => console.log(error));
}



