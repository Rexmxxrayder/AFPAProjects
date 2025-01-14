import React, { useState } from 'react';
const SafeMovieImg = ({ src, fallbackSrc, alt }) => {
    const [imgSrc, setImgSrc] = useState(src);

    const handleError = () => {
        setImgSrc(fallbackSrc);
    };

    return <img className="movieImg" src={imgSrc} alt={alt} onError={handleError} />;
};

export default SafeMovieImg;