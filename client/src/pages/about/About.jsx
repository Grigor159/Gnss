import React, { useEffect, useState } from "react";
import { useTranslation } from "react-i18next";
import { useDispatch, useSelector } from "react-redux";
import { getPartners } from "../../store/slices/homeSlice";
import { Title } from "../../components/animate/Title";
import { aboutImages } from "./data";
import { FullScreenSlide } from "../../components/fullScreenSlide/FullScreenSlide";
import "./About.scss";

const About = () => {
  const { t } = useTranslation();

  const [fullscreenImageIndex, setFullscreenImageIndex] = useState(0);
  const [fullscreenOpen, setFullscreenOpen] = useState(false);

  const sliderImages =
    aboutImages?.map(({ image }) => ({
      original: image,
      thumbnail: image,
    })) || [];

  const openFullscreen = (index) => {
    setFullscreenImageIndex(index);
    setFullscreenOpen(true);
  };

  const closeFullscreen = () => {
    setFullscreenOpen(false);
  };

  fullscreenOpen
    ? (document.body.style.overflow = "hidden")
    : (document.body.style.overflow = "auto");

  const dispatch = useDispatch();

  useEffect(() => {
    dispatch(getPartners());
  }, [dispatch]);

  const { partners } = useSelector((state) => state.home);

  return (
    <section className="about">
      <div className="container">
        <Title text={t("about")} />

        <div className="about">
          <div className="about__main">
            <h3>{t("about_subtitle")}</h3>

            <div className="about__main-context">
              <div
                className="about__main-context-img"
                onClick={() => openFullscreen(0)}
              >
                {aboutImages?.map(({ image, id }) => (
                  <img key={id} src={image} alt="About-Us" />
                ))}
              </div>

              <p>{t("about_text")}</p>
            </div>

            {/* <div className="about__main-imgs">
              {aboutImages?.slice(1, 5)?.map(({ image, id }) => {
                return (
                  <div
                    key={id}
                    className="about__main-imgs-img"
                    onClick={() => openFullscreen(id)}
                  >
                    <img src={image} alt={`About-Us${id}`} />
                  </div>
                );
              })}
            </div> */}
          </div>
        </div>

        {fullscreenOpen && (
          <div className="fullscreen-overlay">
            <FullScreenSlide
              images={sliderImages}
              startIndex={fullscreenImageIndex}
              onClose={closeFullscreen}
            />
          </div>
        )}

        {partners?.length > 0 ? (
          <div className="about__partners">
            <h3>Partners</h3>
            <div className="about__partners-block">
              {partners?.map(({ id, title, image }) => {
                return (
                  <div key={id} className="about__partners-card">
                    <img
                      src={"http://gnss.admin.loc/storage/" + image}
                      alt={title}
                    />
                  </div>
                );
              })}
            </div>
          </div>
        ) : null}
      </div>
    </section>
  );
};

export default About;
