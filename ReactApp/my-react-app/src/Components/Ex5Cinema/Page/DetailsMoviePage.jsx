import 'bootstrap/dist/css/bootstrap.min.css';
import NavBar from '../components/NavBar';
import './MoviePage.css'
import MovieDetails from '../components/MovieDetails';
import React, { useState, useEffect } from 'react';
import { useParams } from "react-router-dom"

const DetailsMoviePage = () => {
    const options = {
        method: 'GET',
        headers: {
            accept: 'application/json',
            Authorization: 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI2Yzg2YTBiMjMzYjFmNmExMWMxNzg5NzhlZjYzMWQ2ZSIsIm5iZiI6MTczNjc1NjQ4My44NzksInN1YiI6IjY3ODRjZDAzNzhjZmNkNzdlZDRlZGU3MCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.9FA7MNZ5TjlWyEVr7IfvOhir758VxtJZt2y1P5rDCBo'
        }
    };

    const [isLoading, setIsLoading] = useState(2);
    const { id } = useParams()
    const [movieData, setMovieData] = useState(null);
    const [movieCredits, setMovieCredits] = useState(null);

    const getMovieDetails = () => {
        fetch('https://api.themoviedb.org/3/movie/' + id, options)
            .then(response => response.json())
            .then(data => {
                setMovieData(data);
                setIsLoading(prev => prev - 1)
                console.log(isLoading);
            })
            .catch(error => {
                console.error("MovieData Error", error);
                setMovieData(null);
                setIsLoading(prev => prev - 1)
            });

        fetch("https://api.themoviedb.org/3/movie/" + id + "/credits", options)
            .then(response => response.json())
            .then(data => {
                setMovieCredits(data);
                setIsLoading(prev => prev - 1)
                console.log(isLoading);
            })
            .catch(error => {
                console.error("MovieCredits Error", error);
                setMovieCredits(null);
                setIsLoading(prev => prev - 1)
            });
    };

    useEffect(() => {
        getMovieDetails();
    }, []);

    return (
        <>
            <NavBar />
            {isLoading > 0 ? (
                <p>Loading...</p>
            ) : (
                <MovieDetails movieData={movieData} movieCredits={movieCredits} />
            )}
        </>
    )
}

export default DetailsMoviePage