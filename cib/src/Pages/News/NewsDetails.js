import { useEffect } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";

export default function NewsDetails() {
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
        <section className="py-6">
        <div className="container">
            <div className="row">
                <div className="w-full mb-lg-0 mb-4">
                    <div className="card">
                        <div className="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                            <a  className="d-block">
                                <img
                                    src="https://images.unsplash.com/photo-1604537466158-719b1972feb8?ixlib=rb-1.2.1&ixid=MnwxMjA3fDF8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2069&q=80"
                                    className="img-fluid border-radius-lg"
                                />
                            </a>
                        </div>
                        <div className="card-body pt-3">
                            <a
                                
                                className="card-title h5 d-block text-darker"
                            >
                                Importance of Sport
                            </a>
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
            </div>
        </div>
    </section>
    );
}
