import 'bootstrap/dist/css/bootstrap.min.css';
import NavBar from '../components/NavBar';
import './MoviePage.css'
import MovieDisplay from '../components/MoviesDisplay';
import React, { useState, useEffect } from 'react';
import { useParams } from "react-router-dom"

const SearchMoviePage = () => {
    const [isLoading, setIsLoading] = useState(true);
    const [moviesData, setMoviesData] = useState(null);
    const options = {
        method: 'GET',
        headers: {
            accept: 'application/json',
            Authorization: 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI2Yzg2YTBiMjMzYjFmNmExMWMxNzg5NzhlZjYzMWQ2ZSIsIm5iZiI6MTczNjc1NjQ4My44NzksInN1YiI6IjY3ODRjZDAzNzhjZmNkNzdlZDRlZGU3MCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.9FA7MNZ5TjlWyEVr7IfvOhir758VxtJZt2y1P5rDCBo'
        }
    };

    
    const { search } = useParams()
    console.log(search);
    const searchMovies = () => {
        fetch('https://api.themoviedb.org/3/search/movie?query=' + search + '&include_adult=false&language=en-US&page=1', options)
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
        searchMovies();
    }, [search]);

    return (
        <>
            <NavBar />
            {isLoading > 0 ? (
                <p>Loading...</p>
            ) : (
                <MovieDisplay moviesData={moviesData.results} />
            )}
        </>
    )
}

export default SearchMoviePage