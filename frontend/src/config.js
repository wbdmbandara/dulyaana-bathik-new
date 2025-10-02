const BACKEND_URL = "http://127.0.0.1:8000/";
const API_URL = `${BACKEND_URL}api/`;
export { API_URL };
export { BACKEND_URL };

export const formatNumber = (num) => {
	if (num === null || num === undefined) return "0.00";
	return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
};

export const formatCurrency = (num) => {
    if (num === null || num === undefined) return "Rs. 0.00";
	return `Rs. ${Number(num).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`;
};