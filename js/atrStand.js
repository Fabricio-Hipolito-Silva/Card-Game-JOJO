$(document).ready(function(){
    $('#ExemploModalCentralizado').modal('show');
  });

  const atrStand = document.getElementById('atributosStand').getContext('2d');
  new Chart(atrStand, {
  type: 'radar',
  data: {
      labels: [
      'Poder Destrutivo', 
      'Velocidade', 
      'Alcance',
      'Persistência', 
      'Precisão', 
      'Potencial'
      ],
      datasets: [{
      data: [3, 1, 3, 2, 3, 1],
      backgroundColor: 'rgba(167, 67, 233, 0.4)',
      borderColor: '#121005',
      borderWidth: 2,
      pointBackgroundColor: '#121005',
      pointRadius: 1, 
      }]
  },
  options: {
responsive: true,
scales: {
r: {
  min:0,
  max:5,
  ticks:{
      stepSize: 1,
      callback: function(value) {
      const letras = {1: 'E', 2: 'D', 3: 'C', 4: 'B', 5: 'A'};
      return letras[value] || '';
      },  
      

  }
  
}
},
plugins: {
legend: {
display: false    
}
},
}
});

