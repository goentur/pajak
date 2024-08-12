import ReactApexChart from 'react-apexcharts';

function RadialBar({persentase, warna}) {
    const options = {
        series: [persentase],
        options: {
            fill: {
                colors: [warna]
            },
            chart: {
                type: 'radialBar',
                sparkline: {
                    enabled: true
                }
            },
            plotOptions: {
                radialBar: {
                    dataLabels: {
                        name: {
                            show: true,
                            offsetY: 13,
                            color : `${warna}`,
                            fontSize: '35px',
                        },
                        value: {
                            show: false
                        },
                    },
                    hollow: {
                        size: '75%',
                    }
                },
            },
            sparkline: {
                enabled: true
            },
            labels: [persentase+'%'],
        }
    };
    return (
    <>
        <ReactApexChart options={options.options} series={options.series} type="radialBar"/>
    </>
    );
  
}

export default RadialBar;