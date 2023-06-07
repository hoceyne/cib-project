import { Link, useNavigate } from "react-router-dom";
import {
    Card,
    CardHeader,
    CardBody,
    CardFooter,
    Input,
    Checkbox,
    Button,
    Typography,
} from "@material-tailwind/react";
import axios from "axios";
import Swal from "sweetalert2";
import { useEffect, useState } from "react";
import Loading from "../../widgets/loaders/Loading";

export function SignIn() {
    const url = process.env.REACT_APP_MED_GUARD_API_URL;
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [loading, setLoading] = useState(false);

    const token = window.localStorage.getItem("token");

    let navigate = useNavigate();

    const login = () => {
        let data = {
            email,
            password,
        };
        axios({
            // Endpoint to send files
            url: url + "/login",
            method: "POST",

            headers: {
                Accept: "Application/json",
            },

            // Attaching the form data
            data: data,
        })
            // Handle the response from backend here
            .then((response) => {
                setLoading(false);
                Swal.fire({
                    title: "Go to dashboard",
                    text: "You are successfuly logged in .",
                    icon: "success",

                    iconColor: "#3dc00c",
                }).then(async () => {
                    // const imgUrl = `data:image/${response.data.profile_picture.extension};base64,${response.data.profile_picture.content}`;

                    // window.localStorage.setItem("profile_picture_url", imgUrl);
                    window.localStorage.setItem("token", response.data.token);
                    navigate("/dashboard/home");
                });
            })

            // Catch errors if any
            .catch((error) => {
                setLoading(false);
                console.log(error);
                Swal.fire({
                    title: error.response?.statusText,
                    text: error.message,
                    icon: "error",
                });
            });
    };

    useEffect(() => {
        if (token) {
            navigate("/dashboard");
        }
    }, []);

    return (
        <>
            <img
                src="/img/auth-bg.jpg"
                className="fixed inset-0 z-0 h-full w-full object-cover"
            />
            <div className="fixed inset-0 z-0 h-full w-full bg-black/50" />
            <div className="container mx-auto p-4 h-screen overflow-auto py-16">
                <Card className="absolute top-2/4 left-2/4 w-full max-w-[24rem] -translate-y-2/4 -translate-x-2/4 my-8">
                    {" "}
                    <CardHeader
                        variant="gradient"
                        color="blue"
                        className="mb-4 grid h-28 place-items-center"
                    >
                        <Typography variant="h3" color="white">
                            Sign In
                        </Typography>
                    </CardHeader>
                    <CardBody className="flex flex-col gap-4">
                        <Input type="email" label="Email" size="lg" />
                        <Input type="password" label="Password" size="lg" />
                        <div className="-ml-2.5">
                            <Checkbox label="Remember Me" />
                        </div>
                    </CardBody>
                    <CardFooter className="pt-0">
                        <Button
                            className="flex items-center text-center justify-center"
                            variant="gradient"
                            fullWidth
                            onClick={() => {
                                login();
                                setLoading(true);
                            }}
                        >
                            {loading ? (
                                <Loading
                                    width="18px"
                                    height={"18px"}
                                    color="white"
                                    weight={"2px"}
                                ></Loading>
                            ) : (
                                ""
                            )}
                            Sign In
                        </Button>
                        <Typography
                            variant="small"
                            className="mt-6 flex justify-center"
                        >
                            Don't have an account?
                            <Link to="/auth/sign-up">
                                <Typography
                                    as="span"
                                    variant="small"
                                    color="blue"
                                    className="ml-1 font-bold"
                                >
                                    Sign up
                                </Typography>
                            </Link>
                        </Typography>
                    </CardFooter>
                </Card>
            </div>
        </>
    );
}

export default SignIn;
