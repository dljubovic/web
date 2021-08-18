const menuButton = document.getElementsByClassName('menu-button')[0];
const menuBar = document.getElementsByClassName('bar');
const navLinks = document.getElementsByClassName('nav-links')[0];
const navbarContainer = document.getElementsByClassName('navbar-container')[0];
const deliveryFormOpacity = document.getElementsByClassName('delivery-data')[0];
const editButton = document.getElementById('edit-button');
const confirmChangesButton = document.getElementById('confirm-changes-button');
const userPersonalData = document.getElementsByClassName('user-personal-data')[0];
const dataChange = document.getElementsByClassName('data-change')[0];
const fName = document.getElementById('fname');
const lName = document.getElementById('lname');
const email = document.getElementById('email');
const contact = document.getElementById('contact');
const adress = document.getElementById('adress');
const city = document.getElementById('city');
const zip = document.getElementById('zip');
const userUpdate = document.getElementsByClassName('user-update')[0];

menuButton.addEventListener('click', () => {
    navLinks.classList.toggle('active');
    menuButton.classList.toggle('active');

    for (var i = 0; i < menuBar.length; i++) {
        if (i == 0) {
            if (menuBar[i].style.transform == "rotate(45deg) translateY(225%)")
                menuBar[i].style.transform = "rotate(0deg) translateY(0%)";
            else 
                menuBar[i].style.transform = "rotate(45deg) translateY(225%)";
        }
        
        else if (i == 1) {
            if (menuBar[i].style.display == "none")
                menuBar[i].style.display = "initial";
            else 
                menuBar[i].style.display = "none";
        }

        else if (i == 2) {
            if (menuBar[i].style.transform == "rotate(-45deg) translateY(-225%)")
                menuBar[i].style.transform = "rotate(0deg) translateY(0%)";
            else 
                menuBar[i].style.transform = "rotate(-45deg) translateY(-225%)";
        }
    }
})

function enableEditting () {
    userUpdate.style.opacity = 0;
    if (confirmChangesButton.hidden == true) {
        confirmChangesButton.hidden = false;
        editButton.style.backgroundColor = "rgb(200, 0, 0)";
        editButton.style.borderColor = "rgb(200, 0, 0)";
        editButton.firstChild.data = "DOVRŠI BEZ SPREMANJA";
        userPersonalData.style.border = "solid red 2px";
        dataChange.style.opacity = "100%";
        fName.disabled = false;
        lName.disabled = false;
        email.disabled = false;
        contact.disabled = false;
        adress.disabled = false;
        city.disabled = false;
        zip.disabled = false;
    }

    else {
        confirmChangesButton.hidden = true;
        editButton.style.backgroundColor = "rgb(138, 0, 0)";
        editButton.style.borderColor = "rgb(138, 0, 0)";
        editButton.firstChild.data = "PROMJENA PODATAKA";
        userPersonalData.style.border = "none";
        dataChange.style.opacity = "0";
        fName.disabled = true;
        lName.disabled = true;
        email.disabled = true;
        contact.disabled = true;
        adress.disabled = true;
        city.disabled = true;
        zip.disabled = true;
    }
}