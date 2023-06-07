import { useEffect, useState } from "react";
import axios from "axios";
import { Link, useNavigate } from "react-router-dom";
import Swal from "sweetalert2";

export default function HospitalizationRequestForm({ patient }) {
    const url = process.env.REACT_APP_MED_GUARD_API_URL;
    const token = window.localStorage.getItem("token");

    const [service_id, setServiceId] = useState("");
    const [time, setTime] = useState("");
    const [hospitalization_date, setHospitalizationDate] = useState("");
    const [bed_id, setBedId] = useState("");
    const [doctor_id, setDoctorId] = useState("");
    const [patient_id, setPatientId] = useState("");
    const [nurse_id, setNurseId] = useState("");

    let navigate = useNavigate();

    const submit = (event) => {
        event.preventDefault();
        axios({
            // Endpoint to send files
            url: url + "/hospitalization/requests/create",
            method: "post",
            data: {
                patient_id,
                service_id,
                time,
                hospitalization_date,
                bed_id,
                doctor_id,
                nurse_id,
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
                    Hospitalization Request
                </h2>

                <form autoComplete="off">
                    <div className="row">
                        <div className="w-full my-2 mb-4">
                            <div className="card">
                                <div className="card-body pt-3">
                                    <h5>Hospitalization</h5>

                                    <div className=" border border-slate-600 rounded-2xl px-4 my-2 py-2">
                                        <div className="mb-4 ">
                                            <label>Service</label>
                                            <div className="input-group">
                                                <input
                                                    value={service_id}
                                                    onChange={(event) => {
                                                        setServiceId(
                                                            event.target.value
                                                        );
                                                    }}
                                                    type="text"
                                                    className="form-control"
                                                    placeholder="Service"
                                                />
                                            </div>
                                        </div>

                                        <div className="row">
                                            <div className="col-md-6">
                                                <label>Time</label>
                                                <div className="input-group mb-4">
                                                    <input
                                                        value={time}
                                                        onChange={(event) => {
                                                            setTime(
                                                                event.target
                                                                    .value
                                                            );
                                                        }}
                                                        className="form-control"
                                                        placeholder="Time"
                                                        aria-label="Time..."
                                                        type="time"
                                                    />
                                                </div>
                                            </div>
                                            <div className="col-md-6 ps-2">
                                                <label>Date</label>
                                                <div className="input-group">
                                                    <input
                                                        value={
                                                            hospitalization_date
                                                        }
                                                        onChange={(event) => {
                                                            setHospitalizationDate(
                                                                event.target
                                                                    .value
                                                            );
                                                        }}
                                                        type="date"
                                                        className="form-control"
                                                        placeholder="Date"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div className="mb-4">
                                            <label>Bed</label>
                                            <div className="input-group mb-4">
                                                <input
                                                    value={bed_id}
                                                    onChange={(event) => {
                                                        setBedId(
                                                            event.target.value
                                                        );
                                                    }}
                                                    type="text"
                                                    className="form-control"
                                                    placeholder="Bed"
                                                    aria-label="Bed..."
                                                />
                                            </div>
                                        </div>
                                        <div className="mb-4">
                                            <label>Doctor</label>
                                            <div className="input-group mb-4">
                                                <input
                                                    value={doctor_id}
                                                    onChange={(event) => {
                                                        setDoctorId(
                                                            event.target.value
                                                        );
                                                    }}
                                                    type="text"
                                                    className="form-control"
                                                    placeholder="Doctor"
                                                    aria-label="Doctor..."
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="row">
                        <div className="w-full my-2 mb-4">
                            <div className="card">
                                <div className="card-body pt-3">
                                    <h5>Patient referred or referred by</h5>

                                    <div className=" border border-slate-600 rounded-2xl px-4 my-2 py-2">
                                        <div className="mb-4 ">
                                            <label>Nurse</label>
                                            <div className="input-group">
                                                <input
                                                    onChange={(event) => {
                                                        setNurseId(
                                                            event.target.value
                                                        );
                                                    }}
                                                    value={nurse_id}
                                                    type="text"
                                                    className="form-control"
                                                    placeholder="Service"
                                                />
                                            </div>
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
                            className="btn bg-gradient-info w-full"
                        >
                            submit
                        </button>
                    </div>
                </form>
            </div>
        </section>
    );
}
