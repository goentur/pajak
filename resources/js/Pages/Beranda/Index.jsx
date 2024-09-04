import { Head, Link } from "@inertiajs/react";
import Layout from "../../Layouts/Layout";
import PieChart from "../Components/ApexChart/PieChart";
import RadialBar from "../Components/ApexChart/RadialBar";
import ProgressBarRealisasi from "../Components/Progress/ProgressBarRealisasi";

function Index({ tahun, services }){
    return (
    <Layout>
        <Head title="Beranda"/>
        <div className="row gap-3 justify-content-center align-self-stretch">
            {
                services.map((service,index) => (
                    service.persentase ?
                    <div key={index} className="col-lg-1 col-md-2 col-sm-2 position-relative border-top border-3 border-primary rounded shadow m-0 p-0">
                        <Link className="stretched-link" href={service.link}></Link>
                        <h6 className="text-center mt-2 fw-normal">{service.name}</h6>
                        <RadialBar persentase={service.service.persen_realisasi} warna={service.color}/>
                    </div>:null
                ))
            }
        </div>
        <div className="px-5">
            <div className="row mt-3 mr-3">
                <div className="col-lg-8 rounded shadow  d-flex justify-content-center align-self-baseline">
                    <div className="" style={{ width: '65%' }}>
                        <PieChart data={services} tahun={tahun} />
                    </div>
                </div>
                <div className="col-lg-4">
                {
                    services.map((service,index) => (
                        service.realisasi ? <ProgressBarRealisasi key={index} nama={service.name} target={service.service.target} realisasi={service.service.realisasi} persenRealisasi={service.service.persen_realisasi} />:null
                    ))
                }
                </div>
            </div>
        </div>
    </Layout>
    )
}

export default Index