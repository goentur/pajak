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
                            offsetY: 6,
                            color : `${warna}`,
                            fontSize: '12rm',
                        },
                        value: {
                            show: false
                        },
                    },
                    hollow: {
                        size: '65%',
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
        <ReactApexChart options={options.options} series={options.series} type="radialBar" className="mt-3 mb-3"/>
    </>
    );
  
}

export default RadialBar;