import { Head, Link } from "@inertiajs/react";
import Layout from "../../Layouts/Layout";
import PieChart from "../Components/ApexChart/PieChart";
import RadialBar from "../Components/ApexChart/RadialBar";
import ProgressBarRealisasi from "../Components/Progress/ProgressBarRealisasi";

function Index({ tahun, services }){
    return (
    <Layout>
        <Head title="BERANDA"/>
        <div className="row grid gap-0 column-gap-3">
            {
                services.map((service,index) => (
                    service.persentase ?
                    <div key={index} className="col position-relative border-top border-5 border-primary rounded shadow">
                        <Link className="stretched-link" href={service.link}></Link>
                        <h4 className="text-center mt-2">{service.name}</h4>
                        <RadialBar persentase={service.service.persen_realisasi} warna={service.color}/>
                    </div>:null
                ))
            }
        </div>
        <div className="row mt-3 mr-3">
            <div className="col-lg-8">
                <div className="rounded shadow">
                <PieChart data={services} tahun={tahun}/>
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
    </Layout>
    )
}

export default Index