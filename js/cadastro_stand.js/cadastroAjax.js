document.getElementById("formStands").addEventListener('submit',function (e){
    const formData = new FormData(this);
    fetch('../../php/cadastro_stand/cadastrar.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.text())
      .then(data => {
        // document.getElementById('resposta').innerHTML = data;
        alert("Stand Cadastrado")
        console.log(data);
      })
      .catch(error =>{
        console.error(error);
        
      });
});



