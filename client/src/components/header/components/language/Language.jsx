import React, { useRef, useState } from "react";
import { useTranslation } from "react-i18next";
import { useDispatch, useSelector } from "react-redux";
import {
  setBurger,
  setLanguage,
  setLanguageValue,
  setOpenBurger,
} from "../../../../store/slices/homeSlice";
import { languageData } from "./data";
import useOutsideClick from "../../../../hooks/useOutsideClick";
import Flag from "react-world-flags";
import "./Language.scss";

const Language = () => {
  const { i18n } = useTranslation();

  const lngRef = useRef();

  const dispatch = useDispatch();

  const { languageValue, burger, openBurger } = useSelector(
    (state) => state.home
  );

  const [openLng, setOpenLng] = useState(false);

  const handleOpenLng = () => {
    setOpenLng(!openLng);
  };

  const handleChangeLng = (code) => {
    setOpenLng(false);
    i18n.changeLanguage(code);
    dispatch(code === "en" ? setLanguageValue("gb") : setLanguageValue(code));
    dispatch(setLanguage(code));

    if (openBurger && burger === "open") {
      dispatch(setBurger(burger === "open" ? "close" : "open"));
      dispatch(setOpenBurger(!openBurger));
    }
  };

  useOutsideClick(lngRef, openLng, setOpenLng);

  return (
    <div className="language" ref={lngRef}>
      <div className="language__choose" onClick={handleOpenLng}>
        <Flag code={languageValue} width="30" height="20" />
      </div>

      <ul
        className={
          !openLng ? "language__dropdown" : "language__dropdown-active"
        }
      >
        {languageData
          .filter((el) => el.country_code !== languageValue)
          .map(({ code, country_code }) => (
            <li key={code}>
              <Flag
                onClick={() => handleChangeLng(code)}
                code={country_code}
                width="30"
                height="20"
              />
            </li>
          ))}
      </ul>
    </div>
  );
};

export default Language;
