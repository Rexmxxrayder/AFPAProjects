import 'bootstrap/dist/css/bootstrap.min.css';
import MovieCard from './MovieCard';
function MovieDisplay({ moviesData }) {
    console.log("aaaa");
    console.log(moviesData);
    const displayMovieCards = () => {
        return moviesData.map((moviesData) => (
            <MovieCard key = {moviesData.id} movieData={moviesData} />
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