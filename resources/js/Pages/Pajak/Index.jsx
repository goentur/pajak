import { Head } from "@inertiajs/react";
import Layout from "../../Layouts/Layout";
import BarChart from "../Components/ApexChart/BarChart";

function Index({ title, name, realisasi }){
    return (
    <Layout>
        <Head title={`PAJAK - ${name}`}/>
        <div className="card border-primary shadow">
            <div className="card-header h3">REALISASI { title }</div>
            <div className="card-body">
                <BarChart name={name} data={realisasi} />
            </div>
        </div>
    </Layout>
    )
}

export default Index