import 'bootstrap/dist/css/bootstrap.min.css';
import './ProductTable.css'
import ToBuy from './ToBuy';
function ProductTable() {
    const products = [
        { category: "Sporting Goods", price: "$49.99", stocked: true, name: "Football" },
        { category: "Sporting Goods", price: "$9.99", stocked: true, name: "Baseball" },
        { category: "Sporting Goods", price: "$29.99", stocked: false, name: "Basketball" },
        { category: "Electronics", price: "$99.99", stocked: true, name: "iPod Touch" },
        { category: "Electronics", price: "$399.99", stocked: false, name: "iPhone 5" },
        { category: "Electronics", price: "$199.99", stocked: true, name: "Nexus 7" }
    ];

    return (
        <>
            <div className="mx-2">
                <table className="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        {products.map((item, index) => (<ToBuy product={item} key={index}/>))}
                    </tbody>
                </table>
            </div>
        </>
    )
}

export default ProductTable