import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import './NavBar.css'
import { Link } from 'react-router-dom';
function NavBar() {
    return (
        <>
            <div className="NavBar">
                <div className="container-fluid">
                    <div className="px-5 d-flex justify-content-between align-items-center">
                        <div className='Title'>FAVFLICKS</div>
                        <form className="Searchbar mx-2 my-2">
                            <div className='input-group'>
                                <input className="form-control border border-0" type="search" placeholder="Search..." aria-label="Search" />
                                <button className="btn d-flex align-items-center" type="submit">
                                    <i className="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                        <Link className="d-flex align-items-center" to="/favorites">Favorites</Link>
                    </div>
                </div>
            </div >
        </>
    )
}

export default NavBar