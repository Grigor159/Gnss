import React from "react";
import { useSelector } from "react-redux";
// import { productsMain } from "../../view/data";
import { Cards } from "../../components/cards/Cards";
import Skeleton from "../../components/skeleton/Skeleton";
import "./Home.scss";

const Home = () => {
  const { allCategories } = useSelector((state) => state.home);

  return (
    <section className="home">
      <div className="container">
        {!allCategories?.length ? (
          <div className="skeleton__cards">
            <Skeleton type="cards" />
          </div>
        ) : (
          <Cards data={allCategories} />
        )}
      </div>
    </section>
  );
};

export default Home;
