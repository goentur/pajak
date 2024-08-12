import { useEffect, useState } from 'react';
import ReactApexChart from 'react-apexcharts';
import { Rupiah } from '../../../Helpers/Rupiah';
import { UbahKeBulan } from '../../../Helpers/UbahKeBulan';

function BarChart({name, data}) {
    const [categories, setCategori] = useState([]);
    const [series, setSeries] = useState([]);

    useEffect(() => {
        if (Array.isArray(data)) {
            const newCategori = [];
            const newSeries = [];
            data.forEach(service => {
                newCategori.push(UbahKeBulan(service.bulan))
                newSeries.push(service.total)
            });
            setCategori(newCategori);
            setSeries(newSeries);
        }
    }, [data]);
    const options = {
        series: [{
                name: name,
                data: series
            }],
                options: {
                    chart: {
                        type: 'bar',
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
                    },
                    xaxis: {
                        categories: categories,
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
        <ReactApexChart options={options.options} series={options.series} type="bar" height="400"/>
    </>
    );
  
}

export default BarChart;