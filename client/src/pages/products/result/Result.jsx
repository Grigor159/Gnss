import React, { useEffect, useState } from "react";
import { useTranslation } from "react-i18next";
import { useParams } from "react-router-dom";
import { useDispatch, useSelector } from "react-redux";
import { getSingleProduct } from "../../../store/slices/homeSlice";
import { useSesionState } from "../../../hooks/useSessionState";
import { Title } from "../../../components/animate/Title";
import { BsFillCalendarCheckFill } from "react-icons/bs";
import noImg from "../../../assets/imgs/noImg.png";
import { moneyFormater } from "../../../helpers/formatters";
import { FullScreenSlide } from "../../../components/fullScreenSlide/FullScreenSlide";
import { Loader } from "../../../components/loader/Loader";
import { Tab } from "./components/tab/Tab";
import { APP_BASE_URL } from "../../../apis/config";
import "./Result.scss";
import { Modal } from "./components/modal/Modal";

const Result = () => {
  const { t } = useTranslation();

  const { id } = useParams();

  const { language, singleProduct } = useSelector((state) => state.home);

  const dispatch = useDispatch();

  useEffect(() => {
    dispatch(getSingleProduct({ id, language }));
  }, [dispatch, id, language]);

  const [fullscreenImageIndex, setFullscreenImageIndex] = useState(0);
  const [fullscreenOpen, setFullscreenOpen] = useState(false);

  const sliderImages = singleProduct?.images?.map(
    (url) =>
      ({
        original: APP_BASE_URL + url,
        thumbnail: APP_BASE_URL + url,
      }) || [],
  );

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

  const [open, setOpen] = useState(false);
  const [active, setActive] = useSesionState("description", "gnss-tab");

  return singleProduct?.length ? (
    <Loader />
  ) : (
    <section className="result">
      <div className="container">
        <Title text={singleProduct?.title} />

        {singleProduct && (
          <div className="result__main">
            <div className="result__main-context">
              <div
                className="result__main-context-img"
                onClick={() => openFullscreen(0)}
              >
                <img
                  src={
                    singleProduct?.images?.length
                      ? APP_BASE_URL + singleProduct?.images[0]
                      : noImg
                  }
                  alt="productImg"
                />
              </div>

              <div className="result__main-context-imgs">
                {sliderImages?.slice(1, 5)?.map((el, index) => {
                  return (
                    <div
                      key={el.original}
                      className="result__main-context-imgs-img"
                      onClick={() => openFullscreen(index + 1)}
                    >
                      <img src={el.original} alt="productImgs" />
                    </div>
                  );
                })}
              </div>

                <div className="result__main-context-box">
                  <div className="result__main-context-box-top">
                    <span># {id}</span>
                    <p>{t("available")}</p>
                  </div>

                  {/* <p className="result__main-context-box-price">{moneyFormater(singleProduct?.price)}</p> */}

                  <button onClick={() => setOpen(true)}>{t("order")}</button>

                  {/* <div className="result__main-context-box-top">
                                            <p>{t("created")}</p>
                                            <span><BsFillCalendarCheckFill /> {singleProduct?.updated_at}</span>
                                        </div> */}

                  <p>{singleProduct?.text}</p>
                </div>
            </div>

            <Tab
              active={active}
              setActive={setActive}
              description={singleProduct?.description}
              files={singleProduct?.files}
              media={singleProduct?.links}
            />
          </div>
        )}

        {fullscreenOpen && (
          <div className="fullscreen-overlay">
            <FullScreenSlide
              images={sliderImages}
              startIndex={fullscreenImageIndex}
              onClose={closeFullscreen}
            />
          </div>
        )}
      </div>
      <Modal open={open} setOpen={setOpen} />
    </section>
  );
};

export default Result;
