import 'bootstrap/dist/css/bootstrap.min.css';
import './MovieDetails.css'
import React, { useState, useEffect } from 'react';
import CastCard from './CastCard'
import SafeMovieImg from './SafeMovieImg';
function MovieDetails({ movieData, movieCredits }) {
    const [fav, updatefav] = useState(true);
    console.log(movieData);
    console.log(movieCredits);

    useEffect(() => {
    }, [fav]);

    function invFav(){
        let favMovies = securityFav();
        if(isFav(movieData.id)){
            let index = favMovies.findIndex(obj => obj.id === movieData.id);
            favMovies.splice(index, 1);
        }else {
            favMovies.push(movieData);
        }

        localStorage.setItem("favMovies", JSON.stringify(favMovies));
        updatefav(prev => !prev)
    }

    function securityFav(){
        let favMovies = localStorage.getItem("favMovies");
        return favMovies === "" || favMovies === null ? [] : JSON.parse(favMovies);
    }

    function isFav(movieId){
        return securityFav().findIndex(obj => obj.id === movieId) !== -1;
    }

    return (
        <>
            <div className="container-fluid mt-3">
                <div className='movie d-flex'>
                    <div id="img">
                        <SafeMovieImg
                            src={"https://image.tmdb.org/t/p/w500" + movieData.poster_path}
                            fallbackSrc="https://static.vecteezy.com/system/resources/previews/011/860/696/non_2x/its-movie-time-free-vector.jpg"
                        />
                        <i className={'fav2 bi ' + (isFav(movieData.id) ? "bi-heart-fill" : "bi-heart")} onClick={invFav}></i>
                    </div>
                    <div className='data container'>
                        <h2>{movieData.title}</h2>
                        <div className='d-flex align-items-baseline'>
                            <div className='movieVariableType'>Release Date:</div>
                            <div className='movieVariableValue'>{movieData.release_date}</div>
                            <div className='movieVariableType'>Runtime:</div>
                            <div className='movieVariableValue'>{movieData.runtime}</div>
                            <div className='movieVariableType'>Rating:</div>
                            <div className='movieVariableValue'>{(movieData.vote_average * 10).toFixed(0) + " %"}</div>
                        </div>
                        <div className='d-flex align-items-baseline'>
                            <div className='movieVariableType'>Genres:</div>
                            {movieData.genres.map((item) => (
                                <div key={item.id} className='movieVariableValue'>{item.name}</div>
                            ))}
                            <div className='movieVariableType'>Status:</div>
                            <div className='movieVariableValue'>{movieData.status}</div>
                        </div>
                        <div className='d-flex align-items-baseline'>
                            <div className='movieVariableType'>Budget:</div>
                            <div className='movieVariableValue'>{Number(movieData.budget).toLocaleString() + "$"}</div>
                            <div className='movieVariableType'>Revenue:</div>
                            <div className='movieVariableValue'>{Number(movieData.revenue).toLocaleString() + "$"}</div>
                        </div>
                        <div className='d-flex align-items-baseline'>
                            <div className='movieVariableType'>Tagline:</div>
                            <div className='movieVariableValue'>{movieData.tagline}</div>
                        </div>
                        <div className='d-flex align-items-baseline'>
                            <div className='movieVariableType'>Overview:</div>
                            <div className='movieVariableValue'>{movieData.overview}</div>
                        </div>
                    </div>
                </div>
                <div className='cast mt-3 row'>
                    <h2>Cast</h2>
                    {movieCredits.cast.map((item) => (
                        <CastCard key={item.id} cast={item} />
                    ))}
                </div>
            </div >
        </>
    )
}

export default MovieDetails