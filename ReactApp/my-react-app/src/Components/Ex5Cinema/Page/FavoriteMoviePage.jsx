import 'bootstrap/dist/css/bootstrap.min.css';
import NavBar from '../components/NavBar';
import './MoviePage.css'
import MovieDisplay from '../components/MoviesDisplay';
import React, { useState, useEffect } from 'react';

const FavoriteMoviePage = () => {
    let favMovies = localStorage.getItem("favMovies");
    let favN = favMovies === "" || favMovies === null ? 0 : JSON.parse(favMovies).lenght;
    console.log(favN);
    const [isLoading, setIsLoading] = useState(favN);
    const [moviesData, setMoviesData] = useState(null);
    const options = {
        method: 'GET',
        headers: {
            accept: 'application/json',
            Authorization: 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI2Yzg2YTBiMjMzYjFmNmExMWMxNzg5NzhlZjYzMWQ2ZSIsIm5iZiI6MTczNjc1NjQ4My44NzksInN1YiI6IjY3ODRjZDAzNzhjZmNkNzdlZDRlZGU3MCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.9FA7MNZ5TjlWyEVr7IfvOhir758VxtJZt2y1P5rDCBo'
        }
    };

    const getPopularMovies = () => {
        fetch('https://api.themoviedb.org/3/movie/popular?language=en-US&page=1', options)
            .then(response => response.json())
            .then(data => {
                setMoviesData(data);
                setIsLoading(false)
            })
            .catch(error => {
                console.error("Erreur lors de la récupération des films populaires:", error);
                setMoviesData(null);
                setIsLoading(false)
            });
    };

    useEffect(() => {
        getPopularMovies();
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