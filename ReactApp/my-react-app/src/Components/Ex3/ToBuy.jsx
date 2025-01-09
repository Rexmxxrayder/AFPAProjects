import 'bootstrap/dist/css/bootstrap.min.css';
import './ToBuy.css';
function ToBuy({ product }, { index }) {
    return (
        <tr key={index}>
            <td className={!product.stocked ? 'red' : ''} scope="col">{product.name}</td>
            <td scope="col">{product.price}</td>
        </tr>
    )
}

export default ToBuy