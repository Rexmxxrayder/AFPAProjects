import 'bootstrap/dist/css/bootstrap.min.css';
import NavBar from '../components/NavBar';
import './MoviePage.css'
import MovieDisplay from '../components/MoviesDisplay';
import React, { useState, useEffect } from 'react';

const FavoriteMoviePage = () => {
    const [isLoading, setIsLoading] = useState(true);
    const [moviesData, setMoviesData] = useState(null);

    const getFavoritesMovies = () => {
        let favMovies = localStorage.getItem("favMovies");
        let data = favMovies === "" || favMovies === null ? [] : JSON.parse(favMovies);
        setMoviesData(data);
        setIsLoading(false);
    };

    useEffect(() => {
        getFavoritesMovies();
    }, []);

    return (
        <>
            <NavBar />       
            {isLoading ? (
                <p>Loading...</p> 
            ) : (
                <MovieDisplay moviesData={moviesData} />  
            )}
        </>
    )
}

export default FavoriteMoviePage