import { ProgressBar } from 'react-bootstrap';
import { Rupiah } from '../../../Helpers/Rupiah';

function ProgressBarRealisasi({ nama, target, realisasi, persenRealisasi }) {
    return (
    <>
        <div className="rounded border-start p-3 border-5 border-primary shadow mb-3">
            <div className="row">
                <div className="col-6 py-1 fw-bold">{Rupiah(realisasi)}</div>
                <div className="col-6 py-1 text-end fw-bold">{ nama }</div>
                <div className="col-12 py-1">
                    <ProgressBar variant='success' animated now={persenRealisasi} />
                </div>
                <div className="col-6 py-1">{ Rupiah(target) }</div>
                <div className="col-6 py-1 text-end">{ persenRealisasi }%</div>
            </div>
        </div>
    </>
    );
  
}

export default ProgressBarRealisasi;