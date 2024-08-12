import Container from 'react-bootstrap/Container';
import Header from './Header';
import Footer from './Footer';

function Layout({children}) {
  return (
  <>
    <Header/>
    <Container fluid="lg" style={{paddingTop: "5rem"}}>
        {children}
    </Container>
    <Footer/>
  </>
  );
}

export default Layout;