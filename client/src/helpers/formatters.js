// format long text
export function cutText(text, maxLength) {
  if (text.length > maxLength) {
    return text.slice(0, maxLength) + "...";
  } else {
    return text;
  }
}
// capitalize text
export function capitalizeText(inputText) {
  const words = inputText.split(" ");
  const capitalizedWords = words.map((word) => {
    if (word.length > 0) {
      return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
    } else {
      return "";
    }
  });
  return capitalizedWords.join(" ");
}

// money formater
export function moneyFormater(num) {
  let usd = Intl.NumberFormat("en-US");
  let formated = "֏ " + usd.format(num);
  return formated;
}
// extract fileName
export function extractFileName(url) {
  const parts = url.split("files/");
  if (parts.length === 2) {
    return parts[1];
  }
  return null;
}
