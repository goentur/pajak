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
            colors: ['#4AB9A8', '#FD9847', '#8666C9', '#33C6E0', '#F14A91', '#7A5BFB', '#33CCCC', '#E34D5E', '#FFD347', '#33C578', '#3A87FA'],
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