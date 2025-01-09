import 'bootstrap/dist/css/bootstrap.min.css';
import './SearchBar.css'
function SearchBar() {
    return (
        <>
            <form className="mx-2 mt-2">
                <input className="form-control me-2" type="search" placeholder="Search..." aria-label="Search" />
                <input className="form-check-input mt-2 me-1" type="checkbox" value="" id="flexCheckDefault" />
                <label className="form-check-label mt-1 light-font" htmlFor="flexCheckDefault">
                    Only show products in stock
                </label>
            </form>
        </>
    )
}

export default SearchBar