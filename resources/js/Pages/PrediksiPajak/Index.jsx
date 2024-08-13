import { Card, CardBody, CardHeader } from "react-bootstrap"
import Layout from "../../Layouts/Layout"
import Line from "./Line"
import { useEffect, useState } from "react"
import { useRoute } from '../../../../vendor/tightenco/ziggy';
import axios from "axios"
import { Head } from "@inertiajs/react"

function Index({kategoriPajak, jenisPajak}) {
    const route = useRoute();

    const [dataGrafik, setDataGrafik] = useState(null);
    const [dataJenisPajak, setDataJenisPajak] = useState(jenisPajak);
    useEffect(() => {
        getDataGrafik(dataJenisPajak);
    }, [dataJenisPajak]);
    
    const getDataGrafik = async (jenisPajak) => {
        try {
            const response = await axios.post(route('prediksi-pajak.data'), { 
                jenisPajak : jenisPajak
            });
            setDataGrafik(response.data);
        } catch (e) {
            console.log(e);
        }
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
        <Head title="PREDIKSI PAJAK"/>
            <Layout>
                <Card>
                    <CardHeader>
                        <form onSubmit={handleSubmit} className="row">
                            <div className="col-lg-4">
                                <select name="kategoriPajak" onChange={handleJenisPajakChange} className="form-control" id="kategoriPajak">
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