import { ErrorMessage, Field, Form, Formik } from "formik";
import * as yup from "yup";
import 'bootstrap/dist/css/bootstrap.min.css';
import './SimpleForm.css';

const SimpleForm = () => {
    const initialValues = {
        genre: "",
        firstName: "",
        lastName: "",
        birthday: "",
        address: {
            number: "",
            road: "",
            city: "",
            zipCode: "",
        },
        contact: {
            phone: "",
            email: "",
        },
    };

    const today = new Date();
    const minAgeDate = new Date(today.setFullYear(today.getFullYear() - 18));
    const phoneRegex = /^(\+33|0)[1-9](\d{1}[\s]?){8}$/;

    const validationSchema = yup.object().shape({
        genre: yup.string()
            .oneOf(["Mr", "Ms", "N/A"])
            .required("Required Field"),
        firstName: yup.string().required("Required Field"),
        lastName: yup.string().required("Required Field"),
        birthday: yup.date()
            .required("Required Field")
            .max(minAgeDate, "Must be over 18 Years Old"),
        address: yup.object().shape({
            number: yup.string().required("Required Field"),
            road: yup.string().required("Required Field"),
            city: yup.string().required("Required Field"),
            zipCode: yup.string().required("Required Field"),
        }),
        contact: yup.object().shape({
            phone: yup.string().matches(phoneRegex, "Invalid PhoneNumber").required("Required Field"),
            email: yup.string().email("Invalid email").required("Required Field"),
        }),
    });

    const submit = (values) => {
        console.log(values);
    };

    return (
        <div className="simple m-3">
            <Formik initialValues={initialValues}
                validationSchema={validationSchema}
                onSubmit={submit}>
                <Form>
                    <div className="card">
                        <div className="card-body">
                            <div className="text-center">
                                <h1>Informations</h1>
                            </div>
                            <hr />
                            <div className="d-flex justify-content-between">
                                <div className="d-flex flex-column">
                                    <div className="d-flex mb-2">
                                        <div className="form-check">
                                            <Field
                                                className="form-check-input"
                                                type="radio"
                                                name="genre"
                                                value="Mr"
                                                id="flexRadioDefault1"
                                            />
                                            <label className="form-check-label" htmlFor="flexRadioDefault1">
                                                Mr
                                            </label>
                                        </div>
                                        <div className="form-check ms-2">
                                            <Field
                                                className="form-check-input"
                                                type="radio"
                                                name="genre"
                                                value="Ms"
                                                id="flexRadioDefault2"
                                            />
                                            <label className="form-check-label" htmlFor="flexRadioDefault2">
                                                Ms
                                            </label>
                                        </div>
                                        <div className="form-check ms-2">
                                            <Field
                                                className="form-check-input"
                                                type="radio"
                                                name="genre"
                                                value="N/A"
                                                id="flexRadioDefault3"
                                            />
                                            <label className="form-check-label" htmlFor="flexRadioDefault3">
                                                N/A
                                            </label>
                                        </div>
                                    </div>
                                    <ErrorMessage name="genre" />
                                    <Field className="" name="firstName" placeholder="FirstName" />
                                    <ErrorMessage name="firstName" />
                                    <Field className="mt-3" name="lastName" placeholder="LastName" />
                                    <ErrorMessage name="lastName" />
                                    <label className="mt-3 form-check-label">
                                        Birthday
                                    </label>
                                    <Field className="" name="birthday" type="date" />
                                    <ErrorMessage name="birthday" />
                                </div>

                                <div className="">
                                    <div className="text-align-center vr"></div>
                                </div>
                                <div className="d-flex flex-column">
                                    <h6>Address</h6>
                                    <Field className="" name="address.number" placeholder="Number" />
                                    <ErrorMessage name="address.number" />
                                    <Field className="mt-3" name="address.road" placeholder="Road" />
                                    <ErrorMessage name="address.road" />
                                    <Field className="mt-3" name="address.city" placeholder="City" />
                                    <ErrorMessage name="address.city" />
                                    <Field className="mt-3" name="address.zipCode" placeholder="ZipCode" />
                                    <ErrorMessage name="address.zipCode" />
                                </div>
                            </div>

                            <hr />
                            <div>
                                <h6>Contact</h6>
                                <div className="d-flex justify-content-between">
                                    <div>
                                        <Field name="contact.phone" placeholder="Phone" />
                                        <ErrorMessage name="contact.phone" />
                                    </div>
                                    <div>
                                        <Field name="contact.email" placeholder="Email" />
                                        <ErrorMessage name="contact.email" />
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div >
                                <div className="d-grid gap-2">
                                    <button className="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </Form>
            </Formik>
        </div>
    );
};

export default SimpleForm