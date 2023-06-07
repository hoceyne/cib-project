import { useEffect } from "react";
import axios from "axios";
import { Link, useNavigate } from "react-router-dom";
import Footer from "../../Components/Footers/Footer";
import IndexNavbar from "../../Components/Navbars/IndexNavbar";

export default function Error404() {
    const url = process.env.REACT_APP_MED_GUARD_API_URL;
    const token = window.sessionStorage.getItem("token");

    let navigate = useNavigate();

    const logout = () => {
        axios({
            // Endpoint to send files
            url: url + "/logout",
            method: "get",
            headers: {
                Authorization: "Bearer " + token,
                Accept: "Application/json",
            },
        })
            // Handle the response from backend here
            .then((response) => {
                window.sessionStorage.clear();
                navigate("/login");
            })

            // Catch errors if any
            .catch((error) => {
                if (error.response.status === 401) {
                    window.sessionStorage.clear();
                    navigate("/login");
                }
            });
    };

    useEffect(() => {}, []);

    return (
        <div>
            <IndexNavbar></IndexNavbar>
            <section className="h-screen bg-white">
                <div className="container">
                    <div className="row text-center items-center justify-center">
                        <img
                            src={require("../../assets/img/404.webp")}
                            className=" !max-w-xl"
                        ></img>
                        <div>
                            <p className="text-3xl text-slate-800 bold">
                                We are sorry, we can't find the page you
                                requested.
                            </p>
                            <Link
                                to={"/"}
                                className="underline text-bold text-lg"
                            >
                                <i className="fa-solid fa-arrow-right mr-2"></i>
                                back to home
                            </Link>
                        </div>
                    </div>
                </div>
            </section>
            <Footer></Footer>
        </div>
    );
}
