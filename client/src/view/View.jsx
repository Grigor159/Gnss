import React, { lazy, Suspense, useEffect } from "react";
import pMinDelay from "p-min-delay";
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import Layout from "../components/layout/Layout";
import {
  getAllCategories,
  getAllChildSubCategories,
  getAllSubCategories,
} from "../store/slices/homeSlice";
import { useDispatch, useSelector } from "react-redux";
// import { productsSub } from "./data";
import ScrollToTop from "../components/scrollToTop/ScrollToTop";

const Home = lazy(() => pMinDelay(import("../pages/home/Home"), 1000));
const About = lazy(() => pMinDelay(import("../pages/about/About"), 500));
const Contact = lazy(() => pMinDelay(import("../pages/contact/Contact"), 500));
const Categories = lazy(() =>
  pMinDelay(import("../pages/products/categories/Categories"), 500)
);
const Sub = lazy(() => pMinDelay(import("../pages/products/sub/Sub"), 500));
const ChildSub = lazy(() =>
  pMinDelay(import("../pages/products/childSub/ChildSub"), 500)
);
const Result = lazy(() =>
  pMinDelay(import("../pages/products/result/Result"), 500)
);
const NotFound = lazy(() => pMinDelay(import("../pages/404/NotFound"), 500));

const View = () => {
  const {
    language,
    openBurger,
    allCategories,
    allSubCategories,
    allChildSubCategories,
  } = useSelector((state) => state.home);

  const dispatch = useDispatch();

  useEffect(() => {
    dispatch(getAllCategories(language));
    dispatch(getAllSubCategories(language));
    dispatch(getAllChildSubCategories(language));
  }, [dispatch, language]);

  return (
    <Router>
      <Suspense fallback={null}>
        <Routes>
          <Route path="/" element={<Layout />}>
            <Route index element={<Home />} />
            {/* Main Categories of products - productsMain*/}
            {allCategories?.map(({ id, title, path }) => {
              return (
                <Route
                  key={id}
                  path={path}
                  element={<Categories id={id} title={title} />}
                />
              );
            })}
            {/*All Sub Categories of products */}
            {allSubCategories?.map(({ id, title, path, parent }) => {
              return (
                <Route
                  key={id}
                  path={path}
                  element={<Sub id={id} parent={parent} title={title} />}
                />
              );
            })}
            {/*All Child Sub Categories of products */}
            {allChildSubCategories?.map(({ id, title, path, parent }) => {
              return (
                <Route
                  key={id}
                  path={path}
                  element={<ChildSub id={id} parent={parent} title={title} />}
                />
              );
            })}
            <Route path="product/:id" element={<Result />} />
            <Route path="about" element={<About />} />
            <Route path="contact" element={<Contact />} />
            <Route path="*" element={<NotFound />} />
          </Route>
        </Routes>
        {!openBurger && <ScrollToTop />}
      </Suspense>
    </Router>
  );
};

export default View;

/* <Route path="for-rent" element={<Rent />} />
<Route path="for-rent/:id" element={<SubRent />} />
<Route path="for-sale" element={<Sale />} />
<Route path="for-sale/:id" element={<SubSale />} />
<Route path="our-services" element={<Services />} /> */
