import Container from 'react-bootstrap/Container';
import Nav from 'react-bootstrap/Nav';
import Navbar from 'react-bootstrap/Navbar';
import ThemeMode from './ThemeMode';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faChartLine, faHistory, faHome } from '@fortawesome/free-solid-svg-icons';
import { Link } from '@inertiajs/react';
import { useRoute } from '../../../vendor/tightenco/ziggy';
import logo from '../../../public/img/logo.png'

function Header() {
  const route = useRoute();
  return (
  <>
    <Navbar collapseOnSelect expand="lg" fixed="top" className="bg-body-tertiary border-bottom">
      <Container>
        <Link className="navbar-brand d-flex align-items-center" href={route('beranda')}><img alt="Logo Pemerintah Kota Pekalongan" src={logo} width="26" height="35" className="d-inline-block align-top" /><div className="ms-2">Pajak Kota Pekalongan</div></Link>
        <Navbar.Toggle aria-controls="responsive-navbar-nav" />
        <Navbar.Collapse id="responsive-navbar-nav">
          <Nav className="me-auto">
            <Link className="nav-link" href={route('beranda')}><FontAwesomeIcon icon={faHome}/> Beranda</Link>
            <Link className="nav-link" href={route('riwayat-pajak.index')}><FontAwesomeIcon icon={faHistory}/> Riwayat Pajak</Link>
            <Link className="nav-link" href={route('prediksi-pajak.index')}><FontAwesomeIcon icon={faChartLine} /> Prediksi Pajak</Link>
          </Nav>
          <Nav>
            <Nav.Link><ThemeMode/></Nav.Link>
          </Nav>
        </Navbar.Collapse>
      </Container>
    </Navbar>
  </>
  );
}

export default Header;