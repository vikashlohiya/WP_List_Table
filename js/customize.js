function validateForm(){
   var name= document.getElementById("name").value;
   var email= document.getElementById("email").value;
    if(name==""){
        alert("Please enter name");
        return false;
    }
    if(email==""){
        alert("Please enter email");
        return false;
    }
}