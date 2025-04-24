document.getElementById("formStands").addEventListener('submit',function (e){
    e.preventDefault();
    const formData = new FormData(this);
    fetch('php/cadastrar.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.text())
      .then(data => {
        document.getElementById('resposta').innerHTML = data;
      })
      .catch(error =>{
        console.error(error);
        
      });
});