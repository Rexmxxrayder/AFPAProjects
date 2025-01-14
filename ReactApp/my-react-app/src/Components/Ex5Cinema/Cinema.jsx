import { BrowserRouter, Route, Routes } from "react-router-dom"
import PopularMoviePage from "./Page/PopularMoviePage";
import DetailsMoviePage from "./Page/DetailsMoviePage";
import FavoriteMoviePage from "./Page/FavoriteMoviePage";
import SearchMoviePage from "./Page/SearchMoviePage";

function Cinema() {
    return (
        <>
            <BrowserRouter>
                <Routes>
                    <Route path="/" element= {<PopularMoviePage/>} />
                    <Route path="details/:id" element= {<DetailsMoviePage/>} />
                    <Route path="favorites" element= {<FavoriteMoviePage/>} />
                    <Route path="search/:search" element= {<SearchMoviePage/>} />
                </Routes>
            </BrowserRouter>
        </>
    )
}

export default Cinema