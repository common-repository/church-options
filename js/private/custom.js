function coOpenTab(evt, secName) {
    // Declare variables
    var i, tabcontent, tablinks;
    
    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    
    // Get all elements with class="tablinks" and remove the "active" class
    linkbutton = document.getElementsByClassName("tablinks");
    for (i = 0; i < linkbutton.length; i++) {
        linkbutton[i].className = linkbutton[i].className.replace(" active", "");
    }
    
    // Show the selected tab and add the "active" class to the button
    document.getElementById(secName).style.display = "block";
    evt.currentTarget.className += " active";
}