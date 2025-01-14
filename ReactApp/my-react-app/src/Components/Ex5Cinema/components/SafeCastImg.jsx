import React, { useState } from 'react';
const SafeCastImg = ({ src, fallbackSrc, alt }) => {
    const [imgSrc, setImgSrc] = useState(src);

    const handleError = () => {
        setImgSrc(fallbackSrc);
    };

    return <img className="castImg" src={imgSrc} alt={alt} onError={handleError} />;
};

export default SafeCastImg;
