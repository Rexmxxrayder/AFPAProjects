import 'bootstrap/dist/css/bootstrap.min.css';
import React, { useState, useEffect } from 'react';
import './MovieCard.css'
function MovieCard({ movieData }) {
    return (
        <>
        <div className="p-2 col-2">
            <div className="card">
                <img src={"https://image.tmdb.org/t/p/w500" + movieData.poster_path} className="card-img-top" alt="..."/>
                {/* <div className="card-body">
                    <h5 className="card-title">Card title</h5>
                    <h6 className="card-subtitle mb-2 text-body-secondary">Card subtitle</h6>
                    <p className="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div> */}
            </div>
        </div>
        </>
    )
}

export default MovieCard