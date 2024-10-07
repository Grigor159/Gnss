import React, { useRef } from "react";
// import { useTranslation } from "react-i18next";
import { useLocation, useNavigate } from "react-router-dom";
import { useDispatch } from "react-redux";
import { AiOutlineArrowLeft, AiOutlineArrowRight } from "react-icons/ai";
import { clearSubCategories } from "../../store/slices/homeSlice";
import { capitalizeText } from "../../helpers/formatters";
// import noImg from "../../assets/imgs/noImg.png";
import { APP_BASE_URL } from "../../apis/config";
import "./Cards.scss";

export const Cards = ({ data }) => {
  // const { t } = useTranslation();

  const { pathname } = useLocation();
  const navigate = useNavigate();
  const dispatch = useDispatch();

  const scrollableDivRef = useRef(null);
  const scroll = 405;

  const scrollLeft = () => {
    const scrollableDiv = scrollableDivRef.current;
    if (scrollableDiv) {
      scrollableDiv.scrollLeft -= scroll;
    }
  };

  const scrollRight = () => {
    const scrollableDiv = scrollableDivRef.current;
    if (scrollableDiv) {
      scrollableDiv.scrollLeft += scroll;
    }
  };

  const handleNavigate = (path) => {
    dispatch(clearSubCategories());
    navigate(path);
  };

  return (
    <div className="scrollable">
      {data?.length > 4 && (
        <AiOutlineArrowLeft className="scrollLeft" onClick={scrollLeft} />
      )}
      <div
        className="cards"
        ref={scrollableDivRef}
        style={{ justifyContent: pathname === "/" ? "center" : null }}
      >
        {data?.map(({ id, path, image, title }) => {
          return (
            <div
              style={{
                backgroundImage: `url(${APP_BASE_URL + image})`,
                backgroundSize: "cover",
                backgroundPosition:"center"
              }}
              key={id}
              onClick={() => handleNavigate(path)}
              className="cards__block"
            >
              {/* <img
                src={image ? APP_BASE_URL + image : noImg}
                alt="img"
              /> */}

              <h3>{capitalizeText(title)}</h3>
              {/* <span>{t("see_more")}</span> */}
            </div>
          );
        })}
      </div>
      {data?.length > 4 && (
        <AiOutlineArrowRight className="scrollRight" onClick={scrollRight} />
      )}
    </div>
  );
};
