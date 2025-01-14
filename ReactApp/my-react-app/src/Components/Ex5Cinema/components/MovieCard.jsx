import 'bootstrap/dist/css/bootstrap.min.css';
import './MovieCard.css'
import React, { useState, useEffect, useRef } from 'react';
import { useNavigate } from 'react-router-dom';
import SafeMovieImg from './SafeMovieImg';
function MovieCard({ movieData }) {
    const [descMin, setDescMin] = useState('');
    const [fav, updatefav] = useState(true);
    const cardRef = useRef(null);
    const ratingValue = (movieData.vote_average * 10).toFixed(0);
    const navigate = useNavigate();

    useEffect(() => {
        const descSizeMax = cardRef.current.offsetHeight * 0.8;
        const movieDesc = movieData.overview.length > descSizeMax
            ? movieData.overview.substr(0, descSizeMax) + "..."
            : movieData.overview;

        setDescMin(movieDesc);

    }, [movieData, fav]);

    function pageDetails() {
        navigate('/details/' + movieData.id);
    }

    function invFav(){
        let favMovies = securityFav();
        if(isFav(movieData.id)){
            favMovies.splice(favMovies.indexOf(movieData.id), 1);
        }else {
            console.log(favMovies.length);
            favMovies.push(movieData.id);
        }

        localStorage.setItem("favMovies", JSON.stringify(favMovies));
        updatefav(prev => !prev)
    }

    function securityFav(){
        let favMovies = localStorage.getItem("favMovies");
        console.log(typeof favMovies);
        return favMovies === "" || favMovies === null ? [] : JSON.parse(favMovies);
    }

    function isFav(movieId){
        return securityFav().indexOf(movieId) !== -1;
    }

    return (
        <>
            <div className="p-2 col-2" ref={cardRef}>
                <div className="card">
                    <SafeMovieImg
                        src={"https://image.tmdb.org/t/p/w500" + movieData.poster_path}
                        fallbackSrc="https://static.vecteezy.com/system/resources/previews/011/860/696/non_2x/its-movie-time-free-vector.jpg"
                    />
                    <div className="movieDetails" onClick={pageDetails}>
                        <div className='movieText'>
                            <div className='movieTitle'>{movieData.original_title}</div>
                            <div className='movieDate'>{movieData.release_date}</div>
                            <div className='movieDesc'>{descMin}</div>
                            <div className='movieRating'>
                                <i className='bi bi-star-fill'></i>
                                {" " + ratingValue + " %"}
                            </div>
                        </div>
                    </div>
                    <i className={'fav bi ' + (isFav(movieData.id) ? "bi-heart-fill" : "bi-heart")} onClick={invFav}></i>
                </div>
            </div>
        </>
    )
}

export default MovieCard