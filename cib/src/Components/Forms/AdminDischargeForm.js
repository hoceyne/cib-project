import { useEffect, useState } from "react";
import axios from "axios";
import { Link, useNavigate } from "react-router-dom";
import Swal from "sweetalert2";

export default function AdminDischargeForm({patient}) {
    const url = process.env.REACT_APP_MED_GUARD_API_URL;
    const token = window.localStorage.getItem("token");

    const [invoice_id, setInvoiceId] = useState("");
    const [date, setDate] = useState("");
    const [service_total_cost, setServiceTotalCost] = useState("");
    const [reciept_id, setRecieptId] = useState("");
    const [discharge_document_type, setDischargeDocumentType] = useState("");
    const [accompanied_on_leaving_by, setAlb] = useState("");

    let navigate = useNavigate();

    const submit = (event) => {
        event.preventDefault();
        axios({
            // Endpoint to send files
            url: url + "/patients/"+patient.id+"/discharge/admin",
            method: "post",
            data: {
                invoice_id,
                date,
                service_total_cost,
                reciept_id,
                discharge_document_type,
                accompanied_on_leaving_by,
               
            },
            headers: {
                Accept: "Application/json",
                Authorization: "Bearer " + token,
            },
        })
            // Handle the response from backend here
            .then((response) => {
                Swal.fire({
                    icon: "success",
                    iconColor: "#3dc00c",
                });
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
        <section className="py-3">
            <div className="container">
                <h2 spellCheck="false" className="text-gradient text-info">
                    Discharge Form
                </h2>
                <form autoComplete="off">
                    <div className="row">
                        <div className="w-full my-2 mb-4">
                            <div className="card">
                                <div className="card-body pt-3">
                                    <div className="mb-4 ">
                                        <label>Invoice Id</label>
                                        <div className="input-group">
                                            <input
                                                value={invoice_id}
                                                onChange={(event) => {
                                                    setInvoiceId(
                                                        event.target.value
                                                    );
                                                }}
                                                type="number"
                                                className="form-control"
                                                placeholder="Invoice Id"
                                            />
                                        </div>
                                    </div>

                                    <div className="mb-4">
                                        <label>Date</label>
                                        <div className="input-group mb-4">
                                            <input
                                                value={date}
                                                onChange={(event) => {
                                                    setDate(event.target.value);
                                                }}
                                                className="form-control"
                                                placeholder="eg. Michael"
                                                aria-label="Date..."
                                                type="text"
                                            />
                                        </div>
                                    </div>
                                    <div className="mb-4">
                                        <label>Service total cost</label>
                                        <div className="input-group mb-4">
                                            <input
                                                value={service_total_cost}
                                                onChange={(event) => {
                                                    setServiceTotalCost(
                                                        event.target.value
                                                    );
                                                }}
                                                type="text"
                                                className="form-control"
                                                placeholder="eg. Jordan"
                                                aria-label="Service total cost..."
                                            />
                                        </div>
                                    </div>
                                    <div className="mb-4">
                                        <label>Reciept Id</label>
                                        <div className="input-group mb-4">
                                            <input
                                                value={reciept_id}
                                                onChange={(event) => {
                                                    setRecieptId(
                                                        event.target.value
                                                    );
                                                }}
                                                type="text"
                                                className="form-control"
                                                placeholder="eg. Jordan"
                                                aria-label="Reciept..."
                                            />
                                        </div>
                                    </div>
                                    <div className="mb-4">
                                        <label>Discharge Document Type</label>
                                        <div className="input-group mb-4">
                                            <input
                                                value={discharge_document_type}
                                                onChange={(event) => {
                                                    setDischargeDocumentType(
                                                        event.target.value
                                                    );
                                                }}
                                                type="number"
                                                className="form-control"
                                                placeholder="Discharge Document Type"
                                                aria-label="Discharge Document Type..."
                                            />
                                        </div>
                                    </div>
                                    <div className="mb-4">
                                        <label>accompanied on leaving by</label>
                                        <div className="input-group mb-4">
                                            <input
                                                value={
                                                    accompanied_on_leaving_by
                                                }
                                                onChange={(event) => {
                                                    setAlb(event.target.value);
                                                }}
                                                className="form-control"
                                                placeholder="eg. Michael"
                                                aria-label="Child of..."
                                                type="text"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="row px-2">
                        <button
                            onClick={(event) => submit(event)}
                            type="submit"
                            className="btn bg-gradient-info w-full "
                        >
                            submit
                        </button>
                    </div>
                </form>
            </div>
        </section>
    );
}
