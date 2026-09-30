import React, { useRef } from "react";
import { useTranslation } from "react-i18next";
import { Title } from "../../components/animate/Title";
import emailjs from "@emailjs/browser";
import contact from "../../assets/imgs/contact2.jpg";
import { oops, success } from "../../components/swal/swal";
import "./Contact.scss";

const Contact = () => {
  const { t } = useTranslation()

  const form = useRef();

  const sendEmail = (e) => {
    e.preventDefault();

    emailjs
      .sendForm(
        process.env.REACT_APP_EMAIL_SERVICE,
        process.env.REACT_APP_EMAIL_TEMPLATE,
        form.current,
        process.env.REACT_APP_EMAIL_SECRET
      )
      .then(
        () => {
          success("Good job!", "Message has been sent!");
        },
        () => {
          oops("Oops...", "Something went wrong!", "error");
        }
      );
    e.target.reset();
  };

  return (
    <section className="contact">
      <div className="container">
        <Title text={t("contact")} />

        <div className="contact__main">
          <form ref={form} onSubmit={sendEmail} className="contact__main-form">
            <div className="contact__main-form-flex">
              <input
                type="text"
                name="fullname"
                placeholder="Full Name *"
                required
              />
              <input
                type="email"
                name="email"
                placeholder="Contact back info *"
                required
              />
            </div>

            <div className="contact__main-form-flex2">
              <textarea
                type="subject"
                name="subject"
                placeholder="Subject *"
                required
              ></textarea>
            </div>

            <button type="submit" value="Send">
              Send
            </button>
          </form>

          <div className="contact__main-img">
            <img src={contact} alt="contact-img" />
          </div>
        </div>
      </div>

      <iframe
        title="map"
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3048.640508815029!2d44.50046887581456!3d40.17255707147997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x406abd0012b37ac1%3A0xede7d34ffba2e619!2sArgishti%207%2F4!5e0!3m2!1sru!2sam!4v1790709465357!5m2!1sru!2sam"
        width="100%"
        height="888"
        style={{ border: 0 }}
        allowFullScreen=""
        loading="lazy"
        referrerPolicy="no-referrer-when-downgrade"
      ></iframe>
    </section>
  );
};

export default Contact;
