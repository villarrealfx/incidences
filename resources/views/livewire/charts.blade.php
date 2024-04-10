<div class="container">
    <div class="row">
        <div class="col">
            <h1 class="text-center bg-warning">
                Interrupciones (NDI) por Distribución Sucre - Mes Abril 2023
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th>Mes - 2023</th>
                        <th>Interrupciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Enero</td>
                        <td>542</td>
                    </tr>
                    <tr>
                        <td>Febrero</td>
                        <td>627</td>
                    </tr>
                    <tr>
                        <td>Marzo</td>
                        <td>670</td>
                    </tr>
                    <tr>
                        <td>Abril</td>
                        <td>808</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-8">
            <canvas id="myChart"></canvas>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.3.0/chart.min.js" integrity="sha512-mlz/Fs1VtBou2TrUkGzX4VoGvybkD9nkeXWJm3rle0DPHssYYx4j+8kIS15T78ttGfmOjH0lLaBXGcShaVkdkg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
        label: '# of Votes',
        data: [12, 19, 3, 5, 2, 3],
        borderWidth: 1
        }]
    },
    options: {
        scales: {
        y: {
            beginAtZero: true
        }
        }
    }
    });
</script>
