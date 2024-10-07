import React from "react";
import { Link } from "react-router-dom";
import Language from "./components/language/Language";
import Routes from "./components/routes/Routes";
import logo from "../../assets/imgs/logo.png";
import { useDispatch, useSelector } from "react-redux";
import { setBurger, setOpenBurger } from "../../store/slices/homeSlice";
import { useOverflow } from "../../hooks/useOverflow";
import "./Header.scss";

export const Header = () => {
  const dispatch = useDispatch();

  const { burger, openBurger } = useSelector((state) => state.home);

  const handleBurger = () => {
    dispatch(setBurger(burger === "open" ? "close" : "open"));
    dispatch(setOpenBurger(!openBurger));
  };

  useOverflow(openBurger);

  return (
    <header className="header">
      <div className="container">
        <nav className="header__nav">
          <div className="header__nav-logo">
            <Link
              to="/"
              onClick={() => window.scrollTo(0, 0)}
              className="header__nav-link"
            >
              <img className="header__nav-icon" src={logo} alt="logo" />
            </Link>

            <div className="header__nav-text">
              <p>Smart</p>
              <p>Solutions</p>
              <p>
                <span>For </span>Success
              </p>
            </div>
          </div>

          <div
            className={`header__nav-right ${
              openBurger ? "header__nav-right-active" : ""
            }`}
          >
            <Routes />
            <Language />
          </div>

          <div className="header__burger" role="button" onClick={handleBurger}>
            <i className={burger}></i>
            <i className={burger}></i>
            <i className={burger}></i>
          </div>
        </nav>
      </div>
    </header>
  );
};
