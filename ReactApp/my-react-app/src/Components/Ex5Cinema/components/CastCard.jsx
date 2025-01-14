import 'bootstrap/dist/css/bootstrap.min.css';
import './CastCard.css';
import SafeCastImg from './SafeCastImg';
function CastCard({ cast }) {
    return (
        <>
            <div className="castCard p-2 col-2">
                <SafeCastImg
                    src={"https://image.tmdb.org/t/p/w500" + cast.profile_path}
                    fallbackSrc="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQIf4R5qPKHPNMyAqV-FjS_OTBB8pfUV29Phg&s"
                />
                <div className='castTitle mt-3'>{cast.name}</div>
            </div>
        </>
    )
}

export default CastCard



