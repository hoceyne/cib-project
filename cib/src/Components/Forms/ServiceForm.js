import { useEffect, useState } from "react";
import axios from "axios";
import { Link, useNavigate } from "react-router-dom";
import Swal from "sweetalert2";

export default function ServiceForm({ patient }) {
    const url = process.env.REACT_APP_MED_GUARD_API_URL;
    const token = window.localStorage.getItem("token");

    const [chief_doctor_id, setChiefDoctorId] = useState("");
    const [medical_staff, setMedicalStaff] = useState("");
    const [paramedical_staff, setParamedicalStaff] = useState("");
    const [bed_capacity, setBedCapacity] = useState("");
    const [activity, setActivity] = useState("");
    const [phone_number, setPhoneNumber] = useState("");
    const [email, setEmail] = useState("");
    const [fax, setFax] = useState("");
    const [activities, setActivities] = useState([]);
    const [main_photo, setMainPhoto] = useState(null);

    const add_activity = (event) => {
        event.preventDefault();
        if (activity) {
            let temp = [...activities, activity];
            setActivities(temp);
        }
    };

    const delete_activity = (event, activity) => {
        event.preventDefault();
        setActivities(activities.filter((element) => element !== activity));
    };

    const [outlook, setOutlook] = useState("");
    const [outlooks, setOutlooks] = useState([]);

    const add_outlook = (event) => {
        event.preventDefault();
        if (outlook) {
            let temp = [...outlooks, outlook];
            setOutlooks(temp);
        }
    };

    const delete_outlook = (event, outlook) => {
        event.preventDefault();
        setOutlooks(outlooks.filter((element) => element !== outlook));
    };

    const [uploaded_images, setUploadedImages] = useState([]);

    const handleFileUpload = (event) => {
        event.preventDefault();
        const files = event.target.files;
        const updatedImages = [...uploaded_images];

        for (let i = 0; i < files.length; i++) {
            if (updatedImages.length < 20) {
                updatedImages.push(files[i]);
            }
        }

        setUploadedImages(updatedImages);
    };

    const handleRemoveImage = (event, index) => {
        event.preventDefault();
        const updatedImages = [...uploaded_images];
        updatedImages.splice(index, 1);
        setUploadedImages(updatedImages);
    };

    let navigate = useNavigate();

    const submit = (event) => {
        event.preventDefault();
        axios({
            // Endpoint to send files
            url: url + "/services/create",
            method: "post",
            data: {
                chief_doctor_id,
                medical_staff,
                paramedical_staff,
                bed_capacity,
                activities,
                outlooks,
                main_photo,
                uploaded_images,
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
                    Service Form
                </h2>

                <form autoComplete="off">
                    <div className="row">
                        <div className="w-full my-2 mb-4">
                            <div className="card">
                                <div className="card-body pt-3">
                                    <h5>Presentation</h5>

                                    <div className=" border border-slate-600 rounded-2xl px-4 my-2 py-2 ">
                                        <div className="mb-4 ">
                                            <label>Chief Doctor</label>
                                            <div className="input-group">
                                                <input
                                                    value={chief_doctor_id}
                                                    onChange={(event) => {
                                                        setChiefDoctorId(
                                                            event.target.value
                                                        );
                                                    }}
                                                    type="text"
                                                    className="form-control"
                                                    placeholder="chief doctor"
                                                />
                                            </div>
                                        </div>

                                        <div className="mb-4">
                                            <label>Medical staff</label>
                                            <div className="input-group mb-4">
                                                <input
                                                    value={medical_staff}
                                                    onChange={(event) => {
                                                        setMedicalStaff(
                                                            event.target.value
                                                        );
                                                    }}
                                                    className="form-control"
                                                    placeholder="fax"
                                                    aria-label="fax..."
                                                    type="text"
                                                />
                                            </div>
                                        </div>
                                        <div className="mb-4">
                                            <label>Paramedical staff</label>
                                            <div className="input-group">
                                                <input
                                                    value={paramedical_staff}
                                                    onChange={(event) => {
                                                        setParamedicalStaff(
                                                            event.target.value
                                                        );
                                                    }}
                                                    type="text"
                                                    className="form-control"
                                                    placeholder="Email"
                                                />
                                            </div>
                                        </div>
                                        <div className="mb-4">
                                            <label>Bed capacity</label>
                                            <div className="input-group mb-4">
                                                <input
                                                    value={bed_capacity}
                                                    onChange={(event) => {
                                                        setBedCapacity(
                                                            event.target.value
                                                        );
                                                    }}
                                                    type="text"
                                                    className="form-control"
                                                    placeholder="Bed Capacity"
                                                    aria-label="Bed Capacity..."
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
                                    <h5>Service Contact</h5>

                                    <div className=" border border-slate-600 rounded-2xl px-4 my-2 py-2 ">
                                        <div className="mb-4 ">
                                            <label>Phone Number</label>
                                            <div className="input-group">
                                                <input
                                                    value={phone_number}
                                                    onChange={(event) => {
                                                        setPhoneNumber(
                                                            event.target.value
                                                        );
                                                    }}
                                                    type="text"
                                                    className="form-control"
                                                    placeholder="Phone Number"
                                                />
                                            </div>
                                        </div>

                                        <div className="mb-4">
                                            <label>Fax</label>
                                            <div className="input-group mb-4">
                                                <input
                                                    value={fax}
                                                    onChange={(event) => {
                                                        setFax(
                                                            event.target.value
                                                        );
                                                    }}
                                                    className="form-control"
                                                    placeholder="fax"
                                                    aria-label="fax..."
                                                    type="text"
                                                />
                                            </div>
                                        </div>
                                        <div className="mb-4">
                                            <label>Email</label>
                                            <div className="input-group">
                                                <input
                                                    value={email}
                                                    onChange={(event) => {
                                                        setEmail(
                                                            event.target.value
                                                        );
                                                    }}
                                                    type="email"
                                                    className="form-control"
                                                    placeholder="Email"
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
                                    <h5>Main service activity</h5>

                                    <div className=" border border-slate-600 rounded-2xl px-4 my-2 py-2 ">
                                        <div className="mb-4 ">
                                            <label>Create Activity</label>
                                            <div className="flex flex-row">
                                                <input
                                                    value={activity}
                                                    onChange={(event) => {
                                                        setActivity(
                                                            event.target.value
                                                        );
                                                    }}
                                                    type="text"
                                                    className="form-control"
                                                    placeholder="chief doctor"
                                                />

                                                <button
                                                    onClick={(event) => {
                                                        add_activity(event);
                                                    }}
                                                >
                                                    <i className="fa-solid fa-add mx-4 hover:text-blue-600 font-bold border-1 border-slate-100 rounded-full p-2 shadow-lg"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div className="row">
                                            <div className="col">
                                                <label>Activities</label>
                                                <ul>
                                                    {activities.length === 0 ||
                                                    !activities ? (
                                                        <div className="w-full flex justify-center flex-col items-center">
                                                            <img
                                                                className="w-fit"
                                                                src={require("../../assets/img/no-files.png")}
                                                            ></img>
                                                            no activities
                                                        </div>
                                                    ) : (
                                                        ""
                                                    )}

                                                    {activities.map(
                                                        (activity, id) => {
                                                            return (
                                                                <li
                                                                    key={id}
                                                                    className="flex flex-row justify-between items-center my-1"
                                                                >
                                                                    {activity}
                                                                    <button
                                                                        onClick={(
                                                                            event
                                                                        ) => {
                                                                            delete_activity(
                                                                                event,
                                                                                activity
                                                                            );
                                                                        }}
                                                                    >
                                                                        <i className="fa-solid fa-trash mx-4 hover:text-red-600 font-bold border-1 border-slate-100 rounded-full p-2 shadow-lg"></i>
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
                        </div>
                    </div>

                    <div className="row">
                        <div className="w-full my-2 mb-4">
                            <div className="card">
                                <div className="card-body pt-3">
                                    <h5>Service Outlook</h5>

                                    <div className=" border border-slate-600 rounded-2xl px-4 my-2 py-2 ">
                                        <div className="mb-4 ">
                                            <label>
                                                Create Service Outlook
                                            </label>
                                            <div className="flex flex-row">
                                                <input
                                                    value={outlook}
                                                    onChange={(event) => {
                                                        setOutlook(
                                                            event.target.value
                                                        );
                                                    }}
                                                    type="text"
                                                    className="form-control"
                                                    placeholder="chief doctor"
                                                />

                                                <button
                                                    onClick={(event) => {
                                                        add_outlook(event);
                                                    }}
                                                >
                                                    <i className="fa-solid fa-add mx-4 hover:text-blue-600 font-bold border-1 border-slate-100 rounded-full p-2 shadow-lg"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div className="row">
                                            <div className="col">
                                                <label>Service Outlooks</label>
                                                <ul>
                                                    {outlooks.length === 0 ||
                                                    !outlooks ? (
                                                        <div className="w-full flex justify-center flex-col items-center">
                                                            <img
                                                                className="w-fit"
                                                                src={require("../../assets/img/empty-box.png")}
                                                            ></img>
                                                            no outlooks yet
                                                            inserted
                                                        </div>
                                                    ) : (
                                                        ""
                                                    )}
                                                    {outlooks.map(
                                                        (outlook, id) => {
                                                            return (
                                                                <li
                                                                    key={id}
                                                                    className="flex flex-row justify-between items-center my-1"
                                                                >
                                                                    {outlook}
                                                                    <button
                                                                        onClick={(
                                                                            event
                                                                        ) => {
                                                                            delete_outlook(
                                                                                event,
                                                                                outlook
                                                                            );
                                                                        }}
                                                                    >
                                                                        <i className="fa-solid fa-trash mx-4 hover:text-red-600 font-bold border-1 border-slate-100 rounded-full p-2 shadow-lg"></i>
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
                        </div>
                    </div>
                    <div className="row">
                        <div className="w-full my-2 mb-4">
                            <div className="card">
                                <div className="card-body pt-3">
                                    <div className="mb-4">
                                        <h5>
                                            Main Photo (only images png or jpg)
                                        </h5>
                                        <div className="input-group">
                                            <input
                                                type="file"
                                                accept="image/*"
                                                className="form-control"
                                                placeholder="Main Photo"
                                                onChange={(event) => {
                                                    setMainPhoto(
                                                        event.target.files[0]
                                                    );
                                                }}
                                            />
                                        </div>
                                    </div>
                                    <div className="mb-4">
                                        <h5>Service images</h5>
                                    </div>
                                    <div className="flex flex-row  w-full  overflow-auto">
                                        <div>
                                            <label>
                                                <div className="w-[400px] h-[200px] rounded-xl border-dashed border-2 border-slate-300 text-slate-500 flex justify-center items-center p-4 hover:cursor-pointer hover:border-blue-500 transition-all duration-200 ease-in-out text-3xl hover:text-blue-500 hover:border-solid">
                                                    <i className="fa-solid fa-add p-2"></i>{" "}
                                                    Add Image{" "}
                                                </div>
                                                <input
                                                    type="file"
                                                    multiple
                                                    accept="image/*"
                                                    onChange={handleFileUpload}
                                                    className="hidden"
                                                />
                                            </label>
                                        </div>
                                        {uploaded_images.map((image, index) => (
                                            <div
                                                key={index}
                                                className="relative min-w-fit mx-2"
                                            >
                                                <img
                                                    src={URL.createObjectURL(
                                                        image
                                                    )}
                                                    className="w-[400px] h-[200px] object-cover rounded-xl "
                                                    alt={`uploaded-image-${index}`}
                                                />
                                                <button
                                                    className="absolute top-0 right-0 m-2 rounded-full bg-black bg-opacity-50 text-white !w-8 h-8 hover:text-red-600 transition-all duration-200"
                                                    onClick={(event) =>
                                                        handleRemoveImage(
                                                            event,
                                                            index
                                                        )
                                                    }
                                                >
                                                    <i className="fa-solid fa-close"></i>
                                                </button>
                                            </div>
                                        ))}
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
