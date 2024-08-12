import { useEffect, useState } from 'react';
import ReactApexChart from 'react-apexcharts';

function PieChart({ data,tahun }) {
    const [warnas, setWarnas] = useState([]);
    const [labels, setLabels] = useState([]);
    const [series, setSeries] = useState([]);
    const [jmlTotal, setJmlTotal] = useState(0);

    useEffect(() => {
        if (Array.isArray(data)) {
            const newWarnas = [];
            const newLabels = [];
            const newSeries = [];
            let total = 0;

            data.forEach(service => {
                if (service.target) {
                    total += parseInt(service.service.target) ?? 0;
                }
            });
            data.forEach(service => {
                if (service.target) {
                    newWarnas.push(service.color)
                    newLabels.push(service.name)
                    newSeries.push(Math.floor((parseInt(service.service.target) / total) * 100))
                } 
            });
            setWarnas(newWarnas);
            setLabels(newLabels);
            setSeries(newSeries);
            // setJmlTotal(total);
        }
    }, [data]);
    const options = {
        series: series,
        options: {
            title: {
                text: 'Target PAD Kota Pekalongan',
                align: 'center',
                margin: 25,
                style: {
                    fontSize:  '30px',
                    fontWeight:  'bold',
                    color:  '#0d6efd'
                },
            },
            subtitle: {
                text: `Tahun ${tahun}`,
                align: 'center',
                margin: 25,
                style: {
                    fontSize:  '25px',
                    color:  '#0d6efd'
                },
            },
            colors: warnas,
            fill: {
                colors: warnas
            },
            chart: {
                type: 'pie',
            },
            labels: labels,
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + "%"
                    },
                    title: {
                        formatter: function (seriesName) {
                            return seriesName
                        }
                    }
                }
            },
            responsive: [{
                breakpoint: 100,
                options: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        },
    };
    return (
    <>
        <ReactApexChart options={options.options} series={options.series} type="pie"/>
    </>
    );
  
}

export default PieChart;