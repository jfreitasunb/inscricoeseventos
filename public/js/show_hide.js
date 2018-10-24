function yesnoCheck() {
    if (document.getElementById('yesCheck').checked) {
        document.getElementById('ifYes').style.display = 'block';
        document.getElementById('ifNo').style.display = 'none';
    }

    if (document.getElementById('noCheck').checked) {
        document.getElementById('ifYes').style.display = 'none';
    }

}