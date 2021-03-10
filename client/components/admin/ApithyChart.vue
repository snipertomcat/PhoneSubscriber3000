<template>
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card">
            <header class="card-header">
                <div class="card-header-title has-text-left">
                    <span class="label">
                        {{this.datasets[this.target].chartData.datasets.label}}
                    </span>
                </div>
            </header>
            <div class="card-content">
                <div class="content">
                    <canvas :width="this.width" :height="this.height" :id="this.target"></canvas>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
    import chart from 'chart.js'
    export default {
        name: "apithy-chart",
        props: ['target','datasets','options','type','width','height'],
        mounted () {
            let ctx = document.getElementById(this.target);

            let data = {
                data: {
                    labels: this.datasets[this.target].chartData.labels,
                    datasets: [{
                        data: this.datasets[this.target].chartData.datasets.data,
                        backgroundColor: this.datasets[this.target].chartData.datasets.backgroundColor
                    }],
                }
            };

            if(this.target === 'companies') {
                data['type'] = 'bar';
                data['options'] = {
                    scales: {
                        yAxes: [{
                            display: true,
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    },
                    legend: {
                        display: false
                    }
                };
            }
            else {
                data['type'] = 'pie';
                data['options'] = {
                    legend: {
                        position: 'bottom'
                    }
                };
            }

            let chart = new Chart(ctx, data);
        }
    }
</script>