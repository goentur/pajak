import { ProgressBar } from 'react-bootstrap';
import { Rupiah } from '../../../Helpers/Rupiah';

function ProgressBarRealisasi({ nama, target, realisasi, persenRealisasi }) {
    return (
    <>
        <div className="rounded border-start p-3 border-3 border-primary shadow mb-3">
            <div className="row">
                <div style={{fontSize:'13px'}} className="col-6 fw-medium">{Rupiah(realisasi)}</div>
                <div style={{fontSize:'13px'}} className="col-6 text-end fw-medium">{ nama }</div>
                <div className="col-12 py-3">
                    <ProgressBar variant='success' style={{height:'6px'}} animated now={persenRealisasi} />
                </div>
                <div style={{fontSize:'13px'}} className="col-6 text-body-secondary">{ Rupiah(target) }</div>
                <div style={{fontSize:'13px'}} className="col-6 text-body-secondary text-end">{ persenRealisasi }%</div>
            </div>
        </div>
    </>
    );
  
}

export default ProgressBarRealisasi;