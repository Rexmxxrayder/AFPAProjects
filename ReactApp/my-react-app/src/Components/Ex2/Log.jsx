import 'bootstrap/dist/css/bootstrap.min.css';
import { useState } from 'react';

function Product() {
    const [isLogged, setIsLogged] = useState(false);
    const InverseLog = () => {
        setIsLogged(prevIsLogged => !prevIsLogged)
    }

    const Login = () => {
        console.log("You are login");
    }

    const Logout = () => {
        console.log("You logout");
    }
    
  return (
    <>
        <h3>You are {!isLogged && "not"} logged</h3>
        <button className = "btn btn-primary" onClick={() => {InverseLog(); isLogged ? Logout() : Login() ;}}>{isLogged ? "Logout": "Login" }</button>
    </>
  )
}

export default Product