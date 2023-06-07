import { useEffect, useState } from "react";
import axios from "axios";
import { Link, useNavigate } from "react-router-dom";
import Swal from "sweetalert2";
import NewsForm from "../../Components/Forms/NewsForm";

export default function News({ limit }) {
    const url = process.env.REACT_APP_MED_GUARD_API_URL;
    const token = window.localStorage.getItem("token");
    const [form, setForm] = useState(false);

    const [newsdetails, setEmployee] = useState({});
    const [method, setMethod] = useState("add");
    const navigate = useNavigate();
    const [news, setNews] = useState([]);

    const getNews = async () => {
        let options = {
            method: "get",
            url: url + "/admin/news",
            headers: {
                Authorization: "Bearer " + token,
                Accept: "Application/json",
            },
        };
        axios(options)
            .then((response) => {
                setNews(response.data);
            })
            .catch((error) => {
                if (error.response.status === 401) {
                    Swal.fire({
                        title: "Please sign in",
                        text: "You are not signed in",
                        icon: "error",
                    }).then(() => {
                        window.localStorage.clear();
                        navigate("/login");
                    });
                }
                if (error.response.status === 500) {
                    navigate("/500");
                } else if (error.response.status === 403) {
                    Swal.fire({
                        title: "Please verify your email",
                        text: "You are not verified press ok to verify your email",
                        icon: "error",
                    }).then(() => {
                        navigate("/email.verify");
                    });
                } else {
                    Swal.fire({
                        title: error.response?.statusText,
                        text: error.response.data.message,
                        icon: "error",
                    });
                }
            });
    };

    const showForm = (action, data) => {
        document.body.style.overflow = "hidden";
        setMethod(action);
        setEmployee(data);
        setForm(true);
    };
    const hide = () => {
        document.body.style.overflow = "auto";
        setForm(false);
    };
    const destroy = (id) => {
        axios({
            // Endpoint to send files
            url: url + "/admin/news/" + id + "/delete",
            method: "delete",
            headers: {
                Accept: "Application/json",
                Authorization: "Bearer " + token,
            },
        })
            // Handle the response from backend here
            .then((response) => {
                Swal.fire({
                    title: "Go to dashboard",
                    text: "You are successfuly logged in .",
                    icon: "success",

                    iconColor: "#3dc00c",
                });
                getNews();
            })

            // Catch errors if any
            .catch((error) => {
                if (error.response.status === 401) {
                    Swal.fire({
                        title: "Please sign in",
                        text: "You are not signed in",
                        icon: "error",
                    }).then(() => {
                        window.localStorage.clear();
                        navigate("/login");
                    });
                }
                if (error.response.status === 500) {
                    navigate("/500");
                } else {
                    Swal.fire({
                        title: error.response?.statusText,
                        text: error.response.data.message,
                        icon: "error",
                    });
                }
            });
    };
    useEffect(() => {
        console.log(news);
        // getNews();
    }, []);
    return (
        <>
            {form ? (
                <div
                    className="fixed bg-black bg-opacity-25 top-0 left-0 z-50 w-full h-screen flex justify-center align-middle items-center overflow-auto py-4"
                    onClick={() => {
                        document.body.style.overflow = "auto";
                        hide();
                    }}
                >
                    <NewsForm
                        action={method}
                        data={newsdetails}
                        hide={hide}
                        getNews={getNews}
                    ></NewsForm>
                </div>
            ) : (
                ""
            )}
            <section className="py-3">
                <div className="container">
                    <div className="flex flex-row justify-between items-center">
                        <h2
                            spellCheck="false"
                            className="text-gradient text-info"
                        >
                            Checkout the latest news
                        </h2>
                        <button
                            onClick={(event) => {
                                showForm(event);
                            }}
                        >
                            <i className="fa-solid fa-add hover:text-blue-600 font-bold border-1 border-slate-100 rounded-full p-2 shadow-lg"></i>
                        </button>
                    </div>
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
                                        Use border utilities to quickly style
                                        the border and border-radius of an
                                        element. Great for images, buttons.
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
                                                <small>
                                                    Posted on 28 February
                                                </small>
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
                                        Use border utilities to quickly style
                                        the border and border-radius of an
                                        element. Great for images, buttons.
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
                                        Use border utilities to quickly style
                                        the border and border-radius of an
                                        element. Great for images, buttons.
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
                    {limit ? (
                        <div className="flex justify-end">
                            <button
                                role="button"
                                className="btn bg-gradient-info"
                                onClick={() => {
                                    navigate("/news");
                                }}
                            >
                                See More{" "}
                                <i className="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    ) : (
                        ""
                    )}
                </div>
            </section>
        </>
    );
}
