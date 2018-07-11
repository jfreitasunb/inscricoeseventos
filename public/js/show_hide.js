function yesnoCheck() {
    if (document.getElementById('yesCheck').checked) {
        document.getElementById('ifYes').style.display = 'block';
    }else{
        document.getElementById('ifYes').style.display = 'none';
        document.getElementById("noCheck").value = 0;
    } 

}