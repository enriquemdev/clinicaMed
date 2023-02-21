
window.addEventListener("load",dashboard)
function dashboard(){
    //1ra parte
const labels = [
    'Enero',
    'Febrero',
    'Marzo',
    'Abril',
    'Mayo',
    'June',
  ];

  const data = {
    labels: labels,
    datasets: [
    {
      label: 'Ganancias semestrales',
      backgroundColor: 'rgb(56, 91, 168)',
      borderColor: 'rgb(13, 21, 39)',
      data: [0, 10, 5, 2, 20, 30, 45], //Esto tiene que ser los datos obtenidos
    },
    {
      label: 'Ganancias semestrales 2',
      backgroundColor: 'rgb(30, 100, 1)',
      borderColor: 'rgb(30, 1, 10)',
      data: [0, 90, 80, 129, 100, 100, 50], //Esto tiene que ser los datos obtenidos
    }
  ]
  };

  const config = {
    type: 'line',
    data: data,
    options: {
        plugins: {
            legend: {
                display: true,
                labels: {
                    color: 'rgb(1, 1, 1)'
                }
            },
            title: {
              display: true,
              text: 'Ganancias'
            }
        }
    }
  };

  //  2da parte
  const myChart = new Chart(
    document.getElementById('myChart'),
    config
  );

}
