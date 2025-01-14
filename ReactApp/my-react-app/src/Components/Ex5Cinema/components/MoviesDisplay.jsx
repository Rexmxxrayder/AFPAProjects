import 'bootstrap/dist/css/bootstrap.min.css';
import React, { useState, useEffect } from 'react';
import MovieCard from './MovieCard';
function MovieDisplay({ moviesData }) {
    console.log(moviesData);
    const displayMovieCards = () => {
        return moviesData.results.map((movieData) => (
            <MovieCard key = {movieData.id} movieData={movieData} />
        ));
    };

    return (
        <>
            <div className="container-fluid">
                <div className='row'>
                    {displayMovieCards()}
                </div>
            </div>
        </>
    )
}

export default MovieDisplay