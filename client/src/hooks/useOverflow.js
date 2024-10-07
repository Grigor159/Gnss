import { useEffect } from "react";

export const useOverflow = (isOverflowHidden) => {
  useEffect(() => {
    if (isOverflowHidden) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "auto";
    }

    return () => {
      document.body.style.overflow = "auto";
    };
  }, [isOverflowHidden]);
};
