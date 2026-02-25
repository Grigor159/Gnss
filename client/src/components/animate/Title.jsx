import React from "react";
// import Zoom from "react-reveal/Zoom";
import Typewritter from "typewriter-effect";
import { capitalizeText } from "../../helpers/formatters";
import { useLocation } from "react-router-dom";
import "./styles.scss";

export const Title = ({ text }) => {
  const { pathname } = useLocation();

  return (
    <h2 className="title">
      <Typewritter
        options={{
          delay: 70,
          strings: pathname?.includes("/product") ? text : capitalizeText(text),
          autoStart: true,
          loop: false,
          cursor: "",
        }}
      />
    </h2>
  );

  // <Zoom cascade>
  //   {pathname?.includes('/product')
  //     ? <h2 className="title">{text}</h2>
  //     : <h2 className="title">{capitalizeText(text)}</h2>
  //   }
  // </Zoom>
};
