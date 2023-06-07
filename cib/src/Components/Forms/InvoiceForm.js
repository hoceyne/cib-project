import React, { useEffect, useRef, useState } from "react";
import axios from "axios";
import Swal from "sweetalert2";
import { Link, useNavigate } from "react-router-dom";

// components

export default function InvoiceForm({ getTests, hide, service, patient }) {
    const token = window.localStorage.getItem("token");
    const url = process.env.REACT_APP_API_URL;
    const [date, setDate] = useState("");
    const [type, setType] = React.useState("");
    const [tax, setTax] = React.useState("");
    const [total, setTotal] = useState(0);
    const [total_amount, setTotalAmount] = useState(0);
    const [description, setDescription] = useState("");
    const [price, setPrice] = useState(0);
    const [services, setServices] = useState([]);

    const add_serivce = (event) => {
        event.preventDefault();
        if (description && price) {
            let temp = [
                ...services,
                { id: services.length, price: price, description: description },
            ];
            const T = temp.reduce((sum, service) => sum + service.price, 0);

            setServices(temp);
            console.log(temp);
        }
    };

    const delete_serivce = (event, id) => {
        event.preventDefault();
        let temp = services
        temp.splice(id, 1);
        const T = temp.reduce((sum, service) => sum + service.price, 0);
        setTotal(T);
        setTotalAmount((T * tax) / 100 + T);
        setServices(temp);
    };
    const navigate = useNavigate();

    const submit = () => {
        axios({
            // Endpoint to send files
            url: url + "/invoices/create",
            method: "post",
            data: {
                type,
                tax,
                services,
                total,
                total_amount,
                
            },
            headers: {
                Accept: "Application/json",
                Authorization: "Bearer " + token,
            },
        })
            // Handle the response from backend here
            .then((response) => {
                hide();
                Swal.fire({
                    title: "Go to dashboard",
                    text: "You are successfuly logged in .",
                    icon: "success",

                    iconColor: "#3dc00c",
                });
                getTests();
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

    useEffect(() => {}, []);
    return (
        <div
            className="relative flex flex-col max-w-[70%]  break-words w-full bg-slate-100  shadow-lg rounded-2xl border-0 m-auto"
            onClick={(e) => e.stopPropagation()}
        >
            <div className="rounded-t-2xl px-4 py-4 mb-0 align-middle">
                <div className="text-center flex justify-between">
                    <h6 className="text-slate-700 text-xl font-bold capitalize">
                        Create Test
                    </h6>
                    <button
                        className="text-white bg-red-600 hover:bg-white hover:border hover:border-solid hover:!text-red-600 w-[32px] h-8 rounded-full shadow hover:shadow-lg outline-none  ease-linear transition-all duration-150"
                        type="button"
                        onClick={() => {
                            hide();
                        }}
                    >
                        <i className="fa-solid fa-close"></i>
                    </button>
                </div>
            </div>
            <div className="flex-auto px-4 pb-4">
                <form>
                    <h6 className="text-slate-400 text-sm my-2 font-bold uppercase">
                        Test details
                    </h6>
                    <div className="flex flex-wrap">
                        <div className="w-full px-4">
                            <div className="relative w-full mb-3">
                                <label
                                    className="block uppercase text-slate-600 text-xs font-bold mb-2"
                                    htmlFor="grid-password"
                                >
                                    date
                                </label>
                                <input
                                    type="text"
                                    className="border-0 px-3 py-3 placeholder-slate-300 text-slate-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                                    placeholder="Date"
                                    onChange={(event) => {
                                        setDate(event.target.value);
                                    }}
                                    value={date}
                                />
                            </div>
                            <div className="relative w-full mb-3">
                                <label
                                    className="block uppercase text-slate-600 text-xs font-bold mb-2"
                                    htmlFor="grid-password"
                                >
                                    Tax
                                </label>
                                <input
                                    type="text"
                                    className="border-0 px-3 py-3 placeholder-slate-300 text-slate-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                                    placeholder="Name"
                                    onChange={(event) => {
                                        setTax(parseInt(event.target.value));
                                    }}
                                    value={tax}
                                />
                            </div>

                            <div className="row">
                                <div className="w-full my-2 mb-4">
                                    <label
                                        className="block uppercase text-slate-600 text-xs font-bold mb-2"
                                        htmlFor="grid-password"
                                    >
                                        {" "}
                                        Services
                                    </label>

                                    <div className=" rounded shadow px-4 my-2 py-2 bg-white ">
                                        <div className="mb-4 ">
                                            <label>Create serivce</label>
                                            <div className="flex flex-row">
                                                <input
                                                    value={description}
                                                    onChange={(event) => {
                                                        setDescription(
                                                            event.target.value
                                                        );
                                                    }}
                                                    type="text"
                                                    className="form-control"
                                                    placeholder="provided service"
                                                />
                                                <input
                                                    value={price}
                                                    onChange={(event) => {
                                                        setPrice(
                                                            parseInt(
                                                                event.target
                                                                    .value
                                                            )
                                                        );
                                                    }}
                                                    type="text"
                                                    className="form-control ml-2"
                                                    placeholder="price"
                                                />

                                                <button
                                                    onClick={(event) => {
                                                        add_serivce(event);
                                                    }}
                                                    className="ml-2"
                                                >
                                                    <i className="fa-solid fa-add  hover:text-blue-600 font-bold border-1 border-slate-100 rounded-full p-2 shadow-lg"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div className="row">
                                            <div className="col">
                                                <label>Services</label>
                                                <ul>
                                                    {services.length === 0 ||
                                                    !services ? (
                                                        <div className="w-full flex justify-center flex-col items-center">
                                                            <img
                                                                className="w-fit"
                                                                src={require("../../assets/img/file-not-found.png")}
                                                            ></img>
                                                            no services
                                                        </div>
                                                    ) : (
                                                        ""
                                                    )}

                                                    {services.map(
                                                        (service, id) => {
                                                            console.log(
                                                                service
                                                            );
                                                            return (
                                                                <li
                                                                    key={id}
                                                                    className="flex flex-row justify-between items-center my-1"
                                                                >
                                                                    <div className="flex flex-row items-center justify-between w-full">
                                                                        <span>
                                                                            {
                                                                                service.description
                                                                            }
                                                                        </span>
                                                                        <span>
                                                                            {service.price +
                                                                                " DZD"}
                                                                        </span>
                                                                    </div>

                                                                    <button
                                                                        onClick={(
                                                                            event
                                                                        ) => {
                                                                            delete_serivce(
                                                                                event,
                                                                                id
                                                                            );
                                                                        }}
                                                                        className="w-fit ml-4"
                                                                    >
                                                                        <i className="fa-solid fa-trash hover:text-red-600 font-bold border-1 border-slate-100 rounded-full p-2 shadow-lg"></i>
                                                                    </button>
                                                                </li>
                                                            );
                                                        }
                                                    )}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div className="row">
                                <div className="w-full my-2 mb-4">
                                    <div className="flex flex-col rounded shadow px-4 my-2 py-2 bg-white ">
                                        <label>
                                            Total: {" " + total + " DZD"}
                                        </label>
                                        <label>Tax:{" " + tax + "%"}</label>
                                        <lalel>
                                            Total Amount:
                                            {" " +
                                                total_amount.toFixed(2) +
                                                " DZD"}
                                        </lalel>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="flex justify-center">
                        <button
                            className=" text-white bg-sky-600 active:bg-sky-700 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 ease-linear transition-all duration-150"
                            type="button"
                            onClick={() => submit()}
                        >
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}
