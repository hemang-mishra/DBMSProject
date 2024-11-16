import React from "react";
import ReactDOM from "react-dom";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import App from "./App";
import CustomerLogin from "./pages/CustomerLogin";
import FarmerLogin from "./pages/FarmerLogin";
import CustomerPage from "./pages/CustomerPage";
import CropDetails from "./pages/CropDetails";
import CartPage from "./pages/userCart";

ReactDOM.createRoot(document.getElementById("root")).render(
  <BrowserRouter>
    <Routes>
      <Route path="/" element={<App />} />
      <Route path="/login/customer" element={<CustomerLogin />} />
      <Route path="/login/farmer" element={<FarmerLogin />} />
      <Route path="/login/customer/Dashboard" element={<CropDetails />} />
      <Route path="/user/cart" element = {<CartPage/>}/>
    </Routes>
  </BrowserRouter>
);
