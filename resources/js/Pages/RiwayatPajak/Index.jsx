import { Card, CardBody, CardHeader } from "react-bootstrap"
import Layout from "../../Layouts/Layout"
import Line from "./Line"
import { useEffect, useState } from "react"
import { useRoute } from '../../../../vendor/tightenco/ziggy';
import axios from "axios"
import { Head } from "@inertiajs/react"

function Index({tahun, kategoriPajak, tahunSebelumnya,jenisPajak}) {
    const route = useRoute();

    const [dataGrafik, setDataGrafik] = useState(null);
    const [dataTahun, setDataTahun] = useState(tahunSebelumnya);
    const [dataJenisPajak, setDataJenisPajak] = useState(jenisPajak);
    useEffect(() => {
        getDataGrafik(dataTahun, dataJenisPajak);
    }, [dataTahun, dataJenisPajak]);
    
    const getDataGrafik = async (tahun,jenisPajak) => {
        try {
            const response = await axios.post(route('riwayat-pajak.data'), { 
                tahun : tahun,
                jenisPajak : jenisPajak
            });
            setDataGrafik(response.data);
        } catch (e) {
            console.log(e);
        }
    };
    
    const handleTahunChange = (selected) => {
        setDataTahun(selected.target.value || null);
    };
    const handleJenisPajakChange = (selected) => {
        setDataJenisPajak(selected.target.value || null);
    };
    const handleSubmit = (e) => {
        e.preventDefault();
        getDataGrafik(dataTahun,dataJenisPajak)
    };
    return (
        <>
        <Head title="RIWAYAT PAJAK"/>
            <Layout>
                <Card>
                    <CardHeader>
                        <form onSubmit={handleSubmit} className="row">
                            <div className="col-lg-2">
                                <select name="tahun" onChange={handleTahunChange} className="form-control" id="tahun">
                                    <option value="">Pilih salah satu</option>
                                    {
                                        tahun.map((data,index) => (
                                            <option key={index} value={data}>{data}</option>
                                        ))
                                    }
                                </select>
                            </div>
                            <div className="col-lg-4">
                                <select name="kategoriPajak" onChange={handleJenisPajakChange} className="form-control" id="kategoriPajak">
                                    <option value="">Pilih salah satu</option>
                                    {
                                        kategoriPajak.map((data,index) => (
                                            <option key={index} value={data}>{data}</option>
                                        ))
                                    }
                                </select>
                            </div>
                        </form>
                    </CardHeader>
                    <CardBody>
                        {dataGrafik!==null ? <Line data={dataGrafik}></Line>:''}
                    </CardBody>
                </Card>
            </Layout>
        </>
    )
}

export default Index