import React, { useEffect, useRef, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import {
  clearSubCategories,
  getHeaderRoutes,
  setBurger,
  setOpenBurger,
} from "../../../../store/slices/homeSlice";
import { NavLink } from "react-router-dom";
import useOutsideClick from "../../../../hooks/useOutsideClick";
import { MdArrowDropDown } from "react-icons/md";
// import { routes } from "./data";
import "./Routes.scss";
import Skeleton from "../../../skeleton/Skeleton";

const Routes = () => {
  const routesRef = useRef();

  const dispatch = useDispatch();

  const { language, routes, burger, openBurger } = useSelector(
    (state) => state.home
  );

  const [open, setopen] = useState(false);

  const handleMenuClose = () => {
    if (openBurger && burger === "open") {
      dispatch(setBurger(burger === "open" ? "close" : "open"));
      dispatch(setOpenBurger(!openBurger));
    }
  };

  useEffect(() => {
    dispatch(getHeaderRoutes(language));
  }, [dispatch, language]);

  useOutsideClick(routesRef, open, setopen);

  return !routes?.length ? (
    <Skeleton type="header" />
  ) : (
    <div className="routes">
      {routes?.map(({ id, path, title, categories }) => {
        return (
          <div key={id} ref={routesRef}>
            <NavLink
              to={path !== "/products" ? path : null}
              onClick={() =>
                path === "/products" ? setopen(!open) : handleMenuClose()
              }
              className={({ isActive }) =>
                isActive && path !== "/products"
                  ? "routes__link active"
                  : "routes__link"
              }
            >
              {" "}
              {title}
              {path === "/products" ? (
                <MdArrowDropDown className="routes__link-down" />
              ) : null}
            </NavLink>

            <ul className={!open ? "routes__dropdown" : "routes__dropdownOpen"}>
              {categories?.map(({ path, title }, index) => {
                return (
                  <li key={index} onClick={() => setopen(!open)}>
                    <NavLink
                      to={path}
                      className="routes__link"
                      onClick={() => {
                        dispatch(clearSubCategories());
                        handleMenuClose();
                      }}
                    >
                      {title}
                    </NavLink>
                  </li>
                );
              })}
            </ul>
          </div>
        );
      })}
    </div>
  );
};
export default Routes;
