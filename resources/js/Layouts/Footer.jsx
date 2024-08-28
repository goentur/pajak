import Container from 'react-bootstrap/Container';
import { Link } from '@inertiajs/react';
import { useRoute } from '../../../vendor/tightenco/ziggy';
import logo from '../../../public/img/logo.png'

function Footer() {
  const route = useRoute();
  return (
  <>
    <footer className="pt-5 pb-3 mt-5 border-top">
        <Container fluid="lg" >
            <div className="row">
                <div className="col-lg-6">
                    <Link href={route('beranda')} className="d-flex align-items-center mb-3 nav-link">
                        <img alt="Logo Pemerintah Kota Pekalongan" src={logo} width="10%" className="mr-5" />
                        <h1 className='mx-2'>Pajak<br />Kota Pekalongan</h1>
                    </Link>
                    <p>Pajak Online merupakan platform website informasi Pajak Daerah Kota Pekalongan yang dikelola oleh Badan Pengelolaan Keuangan dan Aset Daerah Kota Pekalongan</p>
                </div>
                <div className="col-lg-6 row">
                    <div className="col-lg-4">
                        <h5>Layanan Pajak</h5>
                        <ul className="nav flex-column">
                            <li className="nav-item mb-2"><a href="https://bphtb.pekalongankota.go.id" target="_blank" className="nav-link p-0 text-body-secondary">BPHTB Online</a></li>
                            <li className="nav-item mb-2"><a href="https://espt.pekalongankota.go.id" target="_blank" className="nav-link p-0 text-body-secondary">eSPT Pajak Daerah</a></li>
                            <li className="nav-item mb-2"><a href="https://pbb.pekalongankota.go.id/" target="_blank" className="nav-link p-0 text-body-secondary">PBB Online</a></li>
                        </ul>
                    </div>
                    <div className="col-lg-4">
                        <h5>Layanan Non Pajak</h5>
                        <ul className="nav flex-column">
                            <li className="nav-item mb-2"><a href="#" className="nav-link p-0 text-body-secondary">Retribusi Daerah</a></li>
                        </ul>
                    </div>
                    <div className="col-lg-4">
                        <h5>Instansi</h5>
                        <ul className="nav flex-column">
                            <li className="nav-item mb-2"><a href="https://pekalongankota.go.id" target="_blank" className="nav-link p-0 text-body-secondary">Kota Pekalongan</a></li>
                            <li className="nav-item mb-2"><a href="https://bakeuda.pekalongankota.go.id" target="_blank" className="nav-link p-0 text-body-secondary">BPKAD Kota Pekalongan</a></li>
                        </ul>
                    </div>
                </div>
                <div className="col-lg-12 mt-5 row">
                    <div className="col-lg-8">
                        Hak Cipta &copy; 2024 Pajak Online · Dikelola oleh <a href="https://bakeuda.pekalongankota.go.id" target="_blank" className="link-body-emphasis text-decoration-none">Badan Pengelolaan Keuangan dan Aset Daerah Kota Pekalongan</a>.
                    </div>
                    <div className="col-lg-4 row">
                        <a href="#" className="col-lg-6 link-body-emphasis text-decoration-none">S&K Layanan</a>
                        <a href="#" className="col-lg-6 link-body-emphasis text-decoration-none">Kebijakan Privasi</a>
                    </div>
                </div>
            </div>
        </Container>
    </footer>
  </>
  );
}

export default Footer;