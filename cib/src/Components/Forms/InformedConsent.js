import { useEffect, useState } from "react";
import axios from "axios";
import { Link, useNavigate } from "react-router-dom";
import Swal from "sweetalert2";

export default function InformedConsent(service , doctor) {
    const url = process.env.REACT_APP_MED_GUARD_API_URL;
    const token = window.localStorage.getItem("token");

    const [national_id, setNationalId] = useState("");
    const [firstname, setFirstname] = useState("");
    const [lastname, setLastname] = useState("");
    const [blood_group, setBloodGroup] = useState("");
    const [phone_number, setPhoneNumber] = useState("");
    const [gender, setGender] = useState("");
    const [nationality, setNationality] = useState("");
    const [birth_date, setBirthDate] = useState("");
    const [family_situation, setFamilySituation] = useState("");
    const [birth_place, setBirthPlace] = useState("");
    const [person_contact, setPersonContact] = useState("");
    const [person_contact_phone_number, setPersonContactPhoneNumber] =
        useState("");
    const [person_contact_wilaya, setPersonContactWilaya] = useState("");
    const [address_state, setAddressState] = useState("");
    const [address_daira, setAddressDaira] = useState("");
    const [address_city, setAddressCity] = useState("");
    const [address_street, setAddressStreet] = useState("");

    let navigate = useNavigate();

    const submit = (event) => {
        event.preventDefault();
        axios({
            // Endpoint to send files
            url: url + "/patients/create",
            method: "post",
            data: {
                national_id,
                nationality,
                firstname,
                lastname,
                address_city,
                address_daira,
                address_state,
                address_street,
                blood_group,
                phone_number,
                gender,
                birth_date,
                birth_place,
                family_situation,
                person_contact,
                person_contact_phone_number,
                person_contact_wilaya,
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
                    Informed Consent
                </h2>
                <form autoComplete="off">
                    <div className="row">
                        <div className="w-full my-2 mb-4">
                            <div className="card">
                                <div className="card-body pt-3">
                                    <h5>Personal Inforamtion</h5>

                                    <div className=" border border-slate-600 rounded-2xl px-4 my-2 py-2">
                                        <div className="mb-4 ">
                                            <label>National Card Id</label>
                                            <div className="input-group">
                                                <input
                                                    value={national_id}
                                                    onChange={(event) => {
                                                        setNationalId(
                                                            event.target.value
                                                        );
                                                    }}
                                                    type="number"
                                                    className="form-control"
                                                    placeholder="National Card Id"
                                                />
                                            </div>
                                        </div>

                                        <div className="row">
                                            <div className="col">
                                                <label>First Name</label>
                                                <div className="input-group mb-4">
                                                    <input
                                                        value={firstname}
                                                        onChange={(event) => {
                                                            setFirstname(
                                                                event.target
                                                                    .value
                                                            );
                                                        }}
                                                        className="form-control"
                                                        placeholder="eg. Michael"
                                                        aria-label="First Name..."
                                                        type="text"
                                                    />
                                                </div>
                                            </div>
                                            <div className="col ps-2">
                                                <label>Last Name</label>
                                                <div className="input-group mb-4">
                                                    <input
                                                        value={lastname}
                                                        onChange={(event) => {
                                                            setLastname(
                                                                event.target
                                                                    .value
                                                            );
                                                        }}
                                                        type="text"
                                                        className="form-control"
                                                        placeholder="eg. Jordan"
                                                        aria-label="Last Name..."
                                                    />
                                                </div>
                                            </div>
                                            <div className="col ps-2">
                                                <label>Blood Group</label>
                                                <div className="input-group mb-4">
                                                    <input
                                                        value={blood_group}
                                                        onChange={(event) => {
                                                            setBloodGroup(
                                                                event.target
                                                                    .value
                                                            );
                                                        }}
                                                        type="text"
                                                        className="form-control"
                                                        placeholder="eg. Jordan"
                                                        aria-label="Last Name..."
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div className="mb-4">
                                            <label>Phone Number</label>
                                            <div className="input-group mb-4">
                                                <input
                                                    value={phone_number}
                                                    onChange={(event) => {
                                                        setPhoneNumber(
                                                            event.target.value
                                                        );
                                                    }}
                                                    type="number"
                                                    className="form-control"
                                                    placeholder="Phone Number"
                                                    aria-label="Phone Number..."
                                                />
                                            </div>
                                        </div>
                                        <div className="row">
                                            <div className="col">
                                                <label>Gender</label>
                                                <div className="input-group mb-4">
                                                    <input
                                                        value={gender}
                                                        onChange={(event) => {
                                                            setGender(
                                                                event.target
                                                                    .value
                                                            );
                                                        }}
                                                        className="form-control"
                                                        placeholder="eg. Michael"
                                                        aria-label="Child of..."
                                                        type="text"
                                                    />
                                                </div>
                                            </div>
                                            <div className="col ps-2">
                                                <label>Nationality</label>
                                                <div className="input-group mb-4">
                                                    <input
                                                        value={nationality}
                                                        onChange={(event) => {
                                                            setNationality(
                                                                event.target
                                                                    .value
                                                            );
                                                        }}
                                                        type="text"
                                                        className="form-control"
                                                        placeholder="eg. Jordan"
                                                        aria-label="Child of..."
                                                    />
                                                </div>
                                            </div>
                                            <div className="col ps-2">
                                                <label>date of birth</label>
                                                <div className="input-group mb-4">
                                                    <input
                                                        value={birth_date}
                                                        onChange={(event) => {
                                                            setBirthDate(
                                                                event.target
                                                                    .value
                                                            );
                                                        }}
                                                        type="date"
                                                        className="form-control"
                                                        placeholder="eg. Jordan"
                                                        aria-label="date of birth..."
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div className="row">
                                            <div className="col">
                                                <label>Family Situation</label>
                                                <div className="input-group mb-4">
                                                    <input
                                                        value={family_situation}
                                                        onChange={(event) => {
                                                            setFamilySituation(
                                                                event.target
                                                                    .value
                                                            );
                                                        }}
                                                        className="form-control"
                                                        placeholder="eg. Michael"
                                                        aria-label="Family Situation..."
                                                        type="text"
                                                    />
                                                </div>
                                            </div>
                                            <div className="col ps-2">
                                                <label>Birth place</label>
                                                <div className="input-group mb-4">
                                                    <input
                                                        value={birth_place}
                                                        onChange={(event) => {
                                                            setBirthPlace(
                                                                event.target
                                                                    .value
                                                            );
                                                        }}
                                                        type="text"
                                                        className="form-control"
                                                        placeholder="eg. Jordan"
                                                        aria-label="Birth place..."
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div className="row">
                                            <div className="col">
                                                <label>Person Contact</label>
                                                <div className="input-group mb-4">
                                                    <input
                                                        value={person_contact}
                                                        onChange={(event) => {
                                                            setPersonContact(
                                                                event.target
                                                                    .value
                                                            );
                                                        }}
                                                        className="form-control"
                                                        placeholder="eg. Michael"
                                                        aria-label="Person Contact..."
                                                        type="text"
                                                    />
                                                </div>
                                            </div>
                                            <div className="col ps-2">
                                                <label>Phone number</label>
                                                <div className="input-group mb-4">
                                                    <input
                                                        value={
                                                            person_contact_phone_number
                                                        }
                                                        onChange={(event) => {
                                                            setPersonContactPhoneNumber(
                                                                event.target
                                                                    .value
                                                            );
                                                        }}
                                                        type="number"
                                                        className="form-control"
                                                        placeholder="eg. Jordan"
                                                        aria-label="Phone number..."
                                                    />
                                                </div>
                                            </div>
                                            <div className="col ps-2">
                                                <label>Wilaya</label>
                                                <div className="input-group mb-4">
                                                    <input
                                                        value={
                                                            person_contact_wilaya
                                                        }
                                                        onChange={(event) => {
                                                            setPersonContactWilaya(
                                                                event.target
                                                                    .value
                                                            );
                                                        }}
                                                        type="date"
                                                        className="form-control"
                                                        placeholder="eg. Jordan"
                                                        aria-label="Wilaya..."
                                                    />
                                                </div>
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
                                    <h5>Address</h5>
                                    <div className=" border border-slate-600 rounded-2xl px-4 my-2 py-2">
                                        <div className="row">
                                            <div className="col-md-6">
                                                <label>State</label>
                                                <div className="input-group mb-4">
                                                    <input
                                                        value={address_state}
                                                        onChange={(event) => {
                                                            setAddressState(
                                                                event.target
                                                                    .value
                                                            );
                                                        }}
                                                        className="form-control"
                                                        placeholder="State"
                                                        aria-label="State..."
                                                        type="text"
                                                    />
                                                </div>
                                            </div>
                                            <div className="col-md-6">
                                                <label>Daira</label>
                                                <div className="input-group mb-4">
                                                    <input
                                                        value={address_daira}
                                                        onChange={(event) => {
                                                            setAddressDaira(
                                                                event.target
                                                                    .value
                                                            );
                                                        }}
                                                        className="form-control"
                                                        placeholder="daira"
                                                        aria-label="daira..."
                                                        type="text"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div className="row">
                                            <div className="col-md-6 ps-2">
                                                <label>City</label>
                                                <div className="input-group mb-4">
                                                    <input
                                                        value={address_city}
                                                        onChange={(event) => {
                                                            setAddressCity(
                                                                event.target
                                                                    .value
                                                            );
                                                        }}
                                                        type="text"
                                                        className="form-control"
                                                        placeholder="city"
                                                        aria-label="city..."
                                                    />
                                                </div>
                                            </div>
                                            <div className="col-md-6 ps-2">
                                                <label>Street</label>
                                                <div className="input-group mb-4">
                                                    <input
                                                        value={address_street}
                                                        onChange={(event) => {
                                                            setAddressStreet(
                                                                event.target
                                                                    .value
                                                            );
                                                        }}
                                                        type="text"
                                                        className="form-control"
                                                        placeholder="Street"
                                                        aria-label="Street..."
                                                    />
                                                </div>
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
