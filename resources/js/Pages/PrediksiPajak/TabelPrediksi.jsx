import { Table } from "react-bootstrap"

function TabelPrediksi({datas}){
    return (
        <div className="table-responsive">
            <Table style={{fontSize:"11.5px"}} className="table-bordered table-hover table-sm" >
                <thead className="text-center align-middle">
                    <tr>
                        <th className="w-1" rowSpan="2">TAHUN</th>
                        <th className="w-1" rowSpan="2">TARGET</th>
                        <th colSpan="12">BULAN</th>
                        <th rowSpan="2">TOTAL</th>
                        <th rowSpan="2" className="w-1">%</th>
                    </tr>
                    <tr>
                        <th className="w-1">1</th>
                        <th className="w-1">2</th>
                        <th className="w-1">3</th>
                        <th className="w-1">4</th>
                        <th className="w-1">5</th>
                        <th className="w-1">6</th>
                        <th className="w-1">7</th>
                        <th className="w-1">8</th>
                        <th className="w-1">9</th>
                        <th className="w-1">10</th>
                        <th className="w-1">11</th>
                        <th className="w-1">12</th>
                    </tr>
                </thead>
                <tbody>
                    {Object.entries(datas).map(([year, info]) => (
                        <tr key={year}>
                            <td className="text-center fw-bold">{year}</td>
                            <td className="text-end">{info.target}</td>
                            {info.realisasi.map((value, index) => (
                                <td className="text-end" key={index}>{value}</td>
                            ))}
                            <td className="text-end fw-bold">{info.total}</td>
                            <td className="text-end fw-bold">{info.persentase}</td>
                        </tr>
                    ))}
                </tbody>
            </Table>
        </div>
    )
}

export default TabelPrediksi