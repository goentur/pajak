import ReactApexChart from 'react-apexcharts';
import { Rupiah } from '../../Helpers/Rupiah';

function Line({data}) {
    const options = {
        series: data.series,
            options: {
                    chart: {
                        type: 'line',
                        zoom: {
                            enabled: false
                        },
                        toolbar:{
                            tools : {
                                download : false,
                            }
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'straight'
                    },
                    title: {
                        text: data.judul,
                    },
                    xaxis: {
                        categories: data.category,
                    },
                    yaxis: {
                        labels: {
                            formatter: function (value) {
                                return Rupiah(value);
                            }
                        },
                        style: {
                            colors: '#fff',
                        },
                    },
                    tooltip : {
                        y: {
                            formatter: function(val) {
                                return Rupiah(val)
                            },
                            title: {
                                formatter: function (seriesName) {
                                    return seriesName
                                }
                            }
                        },
                    }
                },
    };
    return (
    <>
        <ReactApexChart options={options.options} series={options.series} type="line"/>
    </>
    );
  
}

export default Line;