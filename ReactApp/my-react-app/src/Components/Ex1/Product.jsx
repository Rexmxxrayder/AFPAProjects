import 'bootstrap/dist/css/bootstrap.min.css';
import './Product.css'

function Product({product}) {
  console.log(product.name)
  return (
    <div className='card mx-2' style={{width: 18+"rem"}}>
      <div className="card-body">
        <h5 className="card-title">{product.name}</h5>
        <h6 className="card-subtitle mb-2 text-muted">{product.price}€</h6>
        <p className="card-text">{product.description}</p>
        <h6 className="card-text text-end">{product.stock} en stock</h6>
      </div>
    </div>
  )
}

export default Product