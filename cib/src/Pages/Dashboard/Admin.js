import { useEffect } from "react";
import axios from "axios";
import { Link, useNavigate } from "react-router-dom";

export default function Admin() {
    const url = process.env.REACT_APP_MED_GUARD_API_URL;
    const token = window.localStorage.getItem("token");

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
                window.localStorage.clear();
                navigate("/login");
            })

            // Catch errors if any
            .catch((error) => {
                if (error.response.status === 401) {
                    window.localStorage.clear();
                    navigate("/login");
                }
            });
    };

    useEffect(() => {}, []);

    return (
        <section className="py-3">
            <div className="container">
                <h2 spellCheck="false" className=" text-white">
                    Checkout the latest news
                </h2>
                <div className="row">
                    <div className="w-full my-2 mb-4">
                        <div className="card">
                            <div className="card-body pt-3">
                                <Link
                                    to={"/news/details"}
                                    className="card-title h5 d-block text-darker"
                                >
                                    Importance of Sport
                                </Link>
                                <p className="card-description mb-4">
                                    Use border utilities to quickly style the
                                    border and border-radius of an element.
                                    Great for images, buttons.
                                </p>
                                <div className="author align-items-center">
                                    <img
                                        src="https://demos.creative-tim.com/soft-ui-design-system-pro/assets/img/team-2.jpg"
                                        alt="..."
                                        className="avatar shadow"
                                    />
                                    <div className="name ps-3">
                                        <span>Mathew Glock</span>
                                        <div className="stats">
                                            <small>Posted on 28 February</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="w-full my-2 mb-4">
                        <div className="card">
                            <div className="card-body pt-3">
                                <Link
                                    to={"/news/details"}
                                    className="text-darker card-title h5 d-block"
                                >
                                    How to spend more time home
                                </Link>
                                <p className="card-description mb-4">
                                    Use border utilities to quickly style the
                                    border and border-radius of an element.
                                    Great for images, buttons.
                                </p>
                                <div className="author align-items-center">
                                    <img
                                        src="https://demos.creative-tim.com/soft-ui-design-system-pro/assets/img/ivana-square.jpg"
                                        alt="..."
                                        className="avatar shadow"
                                    />
                                    <div className="name ps-3">
                                        <span>Chriss Smahos</span>
                                        <div className="stats">
                                            <small>Posted 2 min ago</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="w-full my-2 mb-4">
                        <div className="card">
                            <div className="card-body pt-3">
                                <Link
                                    to={"/news/details"}
                                    className="text-darker card-title h5 d-block"
                                >
                                    How to whiten your theets
                                </Link>
                                <p className="card-description mb-4">
                                    Use border utilities to quickly style the
                                    border and border-radius of an element.
                                    Great for images, buttons.
                                </p>
                                <div className="author align-items-center">
                                    <img
                                        src="https://demos.creative-tim.com/soft-ui-design-system-pro/assets/img/marie.jpg"
                                        alt="..."
                                        className="avatar shadow"
                                    />
                                    <div className="name ps-3">
                                        <span>Elijah Miller</span>
                                        <div className="stats">
                                            <small>Posted now</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
